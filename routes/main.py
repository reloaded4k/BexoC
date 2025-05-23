from flask import Blueprint, render_template, flash, redirect, url_for, request
from forms import ContactForm, ShipmentForm, TrackingForm
from models import Contact, Shipment
from app import db
from utils.email import send_contact_email, send_booking_email

main_bp = Blueprint('main', __name__)

@main_bp.route('/')
def index():
    return render_template('index.html')

@main_bp.route('/about')
def about():
    return render_template('about.html')

@main_bp.route('/terms')
def terms():
    return render_template('terms.html')

@main_bp.route('/privacy')
def privacy():
    return render_template('privacy.html')

@main_bp.route('/shipping-terms')
def shipping_terms():
    return render_template('shipping_terms.html')

@main_bp.route('/contact', methods=['GET', 'POST'])
def contact():
    form = ContactForm()
    
    if form.validate_on_submit():
        # Create new contact entry
        contact = Contact(
            name=form.name.data,
            email=form.email.data,
            address=form.address.data,
            contact_number=form.contact_number.data,
            message=form.message.data
        )
        
        # Save to database
        db.session.add(contact)
        db.session.commit()
        
        # Send email notification
        send_contact_email(contact)
        
        # Flash success message
        flash('Your message has been sent successfully. We will get back to you soon!', 'success')
        
        # Redirect to contact page
        return redirect(url_for('main.contact'))
    
    return render_template('contact.html', form=form)

@main_bp.route('/track', methods=['GET', 'POST'])
def track():
    form = TrackingForm()
    shipment = None
    
    if form.validate_on_submit() or request.args.get('tracking_number'):
        # Get tracking number from form or URL parameter
        tracking_number = form.tracking_number.data or request.args.get('tracking_number')
        
        # Query shipment
        shipment = Shipment.query.filter_by(tracking_number=tracking_number).first()
        
        if not shipment:
            flash('Tracking number not found. Please check and try again.', 'danger')
    
    return render_template('track.html', form=form, shipment=shipment)
