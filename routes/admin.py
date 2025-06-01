from flask import Blueprint, render_template, flash, redirect, url_for, request, send_from_directory, current_app
from flask_login import login_user, logout_user, login_required, current_user
from models import Admin, Shipment, TrackingHistory, Invoice
from forms import LoginForm, TrackingUpdateForm, EditShipmentForm, InvoiceUploadForm
from app import db
from werkzeug.security import check_password_hash
from werkzeug.utils import secure_filename
from datetime import datetime
from urllib.parse import urlparse, urljoin
from utils.logging_config import log_error, log_info, log_warning
import os

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
        try:
            # Get admin by username
            admin = Admin.query.filter_by(username=form.username.data).first()
            
            # Check if admin exists and password is correct
            password = form.password.data or ""  # Ensure password is never None
            if admin and check_password_hash(admin.password_hash, password):
                login_user(admin)
                
                log_info(f"Admin login successful: {form.username.data}", {
                    'admin_id': admin.id,
                    'admin_username': admin.username
                }, admin.id)
                
                next_page = request.args.get('next')
                # Validate next_page to prevent open redirect vulnerability using urlparse
                if next_page:
                    # Create a safe function to validate redirects
                    def is_safe_redirect_url(target):
                        host_url = request.host_url
                        redirect_url = urljoin(host_url, target)
                        # Validate that the redirect URL has the same host as our application
                        return urlparse(redirect_url).netloc == urlparse(host_url).netloc
                    
                    # Only redirect to safe URLs
                    if not is_safe_redirect_url(next_page):
                        next_page = None
                        
                return redirect(next_page or url_for('admin.dashboard'))
            else:
                log_warning(f"Failed admin login attempt: {form.username.data}")
                flash('Invalid username or password', 'danger')
        
        except Exception as e:
            log_error(e, {
                'operation': 'admin_login',
                'username': form.username.data
            })
            flash('An error occurred during login. Please try again.', 'danger')
    
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
    
    # Create invoice upload form
    invoice_form = InvoiceUploadForm()
    
    return render_template('admin/view_shipment.html', 
                          shipment=shipment, 
                          update_form=update_form,
                          edit_form=edit_form,
                          invoice_form=invoice_form)

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

@admin_bp.route('/shipment/<tracking_number>/upload-invoice', methods=['POST'])
@login_required
def upload_invoice(tracking_number):
    # Get shipment by tracking number
    shipment = Shipment.query.filter_by(tracking_number=tracking_number).first_or_404()
    
    # Create invoice upload form
    form = InvoiceUploadForm()
    
    if form.validate_on_submit():
        file = form.invoice_file.data
        
        if file:
            # Secure the filename
            filename = secure_filename(file.filename)
            
            # Create unique filename with timestamp
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            unique_filename = f"{tracking_number}_{timestamp}_{filename}"
            
            # Define absolute file path
            upload_folder = os.path.join(current_app.root_path, 'static', 'invoices')
            file_path = os.path.join(upload_folder, unique_filename)
            
            # Ensure upload directory exists
            os.makedirs(upload_folder, exist_ok=True)
            
            # Save the file
            file.save(file_path)
            
            # Remove existing invoice if it exists
            if shipment.invoice:
                # Delete old file
                old_file_path = shipment.invoice.file_path
                if not os.path.isabs(old_file_path):
                    old_file_path = os.path.join(current_app.root_path, old_file_path)
                
                if os.path.exists(old_file_path):
                    os.remove(old_file_path)
                # Delete old invoice record
                db.session.delete(shipment.invoice)
            
            # Create new invoice record
            invoice = Invoice(
                shipment_id=shipment.id,
                filename=filename,
                file_path=file_path,
                file_size=os.path.getsize(file_path),
                content_type=file.content_type,
                uploaded_by=current_user.username
            )
            
            db.session.add(invoice)
            db.session.commit()
            
            flash('Invoice uploaded successfully', 'success')
        else:
            flash('No file selected', 'error')
    else:
        for error in form.errors.values():
            flash(error[0], 'error')
    
    return redirect(url_for('admin.view_shipment', tracking_number=tracking_number))

@admin_bp.route('/shipment/<tracking_number>/delete-invoice', methods=['POST'])
@login_required
def delete_invoice(tracking_number):
    # Get shipment by tracking number
    shipment = Shipment.query.filter_by(tracking_number=tracking_number).first_or_404()
    
    if shipment.invoice:
        # Delete file from filesystem
        file_path = shipment.invoice.file_path
        if not os.path.isabs(file_path):
            file_path = os.path.join(current_app.root_path, file_path)
        
        if os.path.exists(file_path):
            os.remove(file_path)
        
        # Delete invoice record
        db.session.delete(shipment.invoice)
        db.session.commit()
        
        flash('Invoice deleted successfully', 'success')
    else:
        flash('No invoice found', 'error')
    
    return redirect(url_for('admin.view_shipment', tracking_number=tracking_number))
