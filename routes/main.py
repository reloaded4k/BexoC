from flask import Blueprint, render_template, flash, redirect, url_for, request, send_from_directory, abort, current_app
from forms import ContactForm, ShipmentForm, TrackingForm
from models import Contact, Shipment, Invoice
from app import db
from utils.email import send_contact_email, send_booking_email
from utils.logging_config import log_error, log_info, log_warning
import os
import mimetypes

main_bp = Blueprint('main', __name__)

@main_bp.route('/')
def index():
    return render_template('index.html')

@main_bp.route('/about')
def about():
    return render_template('pages/about.html')

@main_bp.route('/terms')
def terms():
    return render_template('pages/terms-conditions.html')

@main_bp.route('/privacy')
def privacy():
    return render_template('pages/privacy-policy.html')

@main_bp.route('/shipping-terms')
def shipping_terms():
    return render_template('pages/shipping-terms.html')

@main_bp.route('/contact', methods=['GET', 'POST'])
def contact():
    form = ContactForm()
    
    if form.validate_on_submit():
        try:
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
            
            log_info(f"New contact form submission from {form.email.data}", {
                'name': form.name.data,
                'email': form.email.data
            })
            
            # Send email notification
            try:
                send_contact_email(contact)
                log_info(f"Contact email sent successfully for {form.email.data}")
            except Exception as email_error:
                log_error(email_error, {
                    'operation': 'send_contact_email',
                    'contact_email': form.email.data
                })
                # Still show success to user since contact was saved
            
            # Flash success message
            flash('Your message has been sent successfully. We will get back to you soon!', 'success')
            
            # Redirect to contact page
            return redirect(url_for('main.contact'))
            
        except Exception as e:
            db.session.rollback()
            log_error(e, {
                'operation': 'contact_form_submission',
                'form_data': {
                    'name': form.name.data,
                    'email': form.email.data
                }
            })
            flash('An error occurred while processing your message. Please try again.', 'danger')
    
    return render_template('contact.html', form=form)

@main_bp.route('/track', methods=['GET', 'POST'])
def track():
    form = TrackingForm()
    shipment = None
    
    if form.validate_on_submit() or request.args.get('tracking_number'):
        try:
            # Get tracking number from form or URL parameter
            tracking_number = form.tracking_number.data or request.args.get('tracking_number')
            
            log_info(f"Tracking lookup attempted for: {tracking_number}")
            
            # Query shipment
            shipment = Shipment.query.filter_by(tracking_number=tracking_number).first()
            
            if not shipment:
                log_warning(f"Tracking number not found: {tracking_number}")
                flash('Tracking number not found. Please check and try again.', 'danger')
            else:
                log_info(f"Tracking lookup successful for: {tracking_number}")
        
        except Exception as e:
            log_error(e, {
                'operation': 'tracking_lookup',
                'tracking_number': tracking_number if 'tracking_number' in locals() else 'unknown'
            })
            flash('An error occurred while looking up your tracking number. Please try again.', 'danger')
    
    return render_template('track.html', form=form, shipment=shipment)

@main_bp.route('/download-invoice/<tracking_number>')
def download_invoice(tracking_number):
    # Get shipment by tracking number
    shipment = Shipment.query.filter_by(tracking_number=tracking_number).first()
    
    if not shipment:
        abort(404, description="Shipment not found")
    
    if not shipment.invoice:
        abort(404, description="Invoice not found for this shipment")
    
    # Ensure we have a valid file path
    file_path = shipment.invoice.file_path
    
    # If file path is relative, make it absolute
    if not os.path.isabs(file_path):
        file_path = os.path.join(current_app.root_path, file_path)
    
    # Security check - ensure file exists
    if not os.path.exists(file_path):
        current_app.logger.error(f"Invoice file not found at path: {file_path}")
        abort(404, description="Invoice file not found")
    
    # Get directory and filename using the corrected path
    directory = os.path.dirname(file_path)
    filename = os.path.basename(file_path)
    
    # Determine content type for better iOS compatibility
    content_type = shipment.invoice.content_type
    if not content_type:
        content_type, _ = mimetypes.guess_type(filename)
        if not content_type:
            content_type = 'application/octet-stream'
    
    # Send file with proper headers for iOS Safari compatibility
    return send_from_directory(
        directory, 
        filename, 
        as_attachment=True, 
        download_name=shipment.invoice.filename,
        mimetype=content_type
    )
