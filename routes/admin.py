from flask import Blueprint, render_template, flash, redirect, url_for, request
from flask_login import login_user, logout_user, login_required, current_user
from models import Admin, Shipment, TrackingHistory
from forms import LoginForm, TrackingUpdateForm, EditShipmentForm
from app import db
from werkzeug.security import check_password_hash
from datetime import datetime

admin_bp = Blueprint('admin', __name__)

@admin_bp.route('/')
@login_required
def dashboard():
    # Get count statistics
    total_shipments = Shipment.query.count()
    in_transit = Shipment.query.filter(Shipment.current_status == "In Transit").count()
    processing = Shipment.query.filter(Shipment.current_status == "Processing").count()
    on_hold = Shipment.query.filter(Shipment.current_status.like("%Hold%")).count()
    
    # Get recent bookings
    recent_bookings = Shipment.query.order_by(Shipment.created_at.desc()).limit(5).all()
    
    return render_template('admin/dashboard.html', 
                          total_shipments=total_shipments,
                          in_transit=in_transit,
                          processing=processing,
                          on_hold=on_hold,
                          recent_bookings=recent_bookings)

@admin_bp.route('/login', methods=['GET', 'POST'])
def login():
    # Redirect if already logged in
    if current_user.is_authenticated:
        return redirect(url_for('admin.dashboard'))
    
    form = LoginForm()
    
    if form.validate_on_submit():
        # Get admin by username
        admin = Admin.query.filter_by(username=form.username.data).first()
        
        # Check if admin exists and password is correct
        password = form.password.data or ""  # Ensure password is never None
        if admin and check_password_hash(admin.password_hash, password):
            login_user(admin)
            next_page = request.args.get('next')
            # Validate next_page to prevent open redirect vulnerability
            if next_page:
                # Only allow relative URLs within our site
                if not next_page.startswith('/'):
                    next_page = None
                # Further protection: ensure no external redirects using double-slash
                elif '//' in next_page:
                    next_page = None
            return redirect(next_page or url_for('admin.dashboard'))
        else:
            flash('Invalid username or password', 'danger')
    
    return render_template('admin/login.html', form=form)

@admin_bp.route('/logout')
@login_required
def logout():
    logout_user()
    flash('You have been logged out', 'success')
    return redirect(url_for('admin.login'))

@admin_bp.route('/bookings')
@login_required
def bookings():
    # Get page number from request
    page = request.args.get('page', 1, type=int)
    
    # Get search query if any
    search = request.args.get('search', '')
    
    # Base query
    query = Shipment.query
    
    # Apply search filter if provided
    if search:
        query = query.filter(
            (Shipment.tracking_number.ilike(f'%{search}%')) |
            (Shipment.sender_name.ilike(f'%{search}%')) |
            (Shipment.recipient_name.ilike(f'%{search}%')) |
            (Shipment.current_status.ilike(f'%{search}%'))
        )
    
    # Paginate results
    shipments = query.order_by(Shipment.created_at.desc()).paginate(
        page=page, per_page=10, error_out=False)
    
    return render_template('admin/bookings.html', shipments=shipments, search=search)

@admin_bp.route('/shipment/<tracking_number>', methods=['GET', 'POST'])
@login_required
def view_shipment(tracking_number):
    # Get shipment by tracking number
    shipment = Shipment.query.filter_by(tracking_number=tracking_number).first_or_404()
    
    # Create tracking update form
    update_form = TrackingUpdateForm()
    
    # Process tracking update form submission
    if request.method == 'POST' and update_form.validate_on_submit():
        # Add tracking update
        shipment.add_tracking_update(update_form.status.data, update_form.notes.data)
        
        # Save changes
        db.session.commit()
        
        flash('Tracking update added successfully', 'success')
        return redirect(url_for('admin.view_shipment', tracking_number=tracking_number))
    
    # Create edit form but don't process it here
    edit_form = EditShipmentForm(obj=shipment)
    
    return render_template('admin/view_shipment.html', 
                          shipment=shipment, 
                          update_form=update_form,
                          edit_form=edit_form)

@admin_bp.route('/shipment/<tracking_number>/edit', methods=['GET', 'POST'])
@login_required
def edit_shipment(tracking_number):
    # Get shipment by tracking number
    shipment = Shipment.query.filter_by(tracking_number=tracking_number).first_or_404()
    
    # Create edit form
    edit_form = EditShipmentForm(obj=shipment)
    
    # Process edit form submission
    if request.method == 'POST' and edit_form.validate_on_submit():
        # Update shipment data
        edit_form.populate_obj(shipment)
        
        # Save changes
        db.session.commit()
        
        flash('Shipment details updated successfully', 'success')
        return redirect(url_for('admin.view_shipment', tracking_number=tracking_number))
    
    return render_template('admin/edit_shipment.html', 
                          shipment=shipment, 
                          edit_form=edit_form)

@admin_bp.route('/shipment/<tracking_number>/delete', methods=['POST'])
@login_required
def delete_shipment(tracking_number):
    # Get shipment by tracking number
    shipment = Shipment.query.filter_by(tracking_number=tracking_number).first_or_404()
    
    # Delete shipment
    db.session.delete(shipment)
    db.session.commit()
    
    flash('Shipment deleted successfully', 'success')
    return redirect(url_for('admin.bookings'))
