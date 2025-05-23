from flask import Blueprint, render_template, flash, redirect, url_for
from forms import ShipmentForm
from models import Shipment
from app import db
from utils.email import send_booking_email

shipment_bp = Blueprint('shipment', __name__)

@shipment_bp.route('/book', methods=['GET', 'POST'])
def book():
    form = ShipmentForm()
    
    if form.validate_on_submit():
        # Create new shipment
        shipment = Shipment(
            # Sender information
            sender_name=form.sender_name.data,
            sender_email=form.sender_email.data,
            sender_address=form.sender_address.data,
            sender_contact=form.sender_contact.data,
            
            # Recipient information
            recipient_name=form.recipient_name.data,
            recipient_email=form.recipient_email.data,
            recipient_address=form.recipient_address.data,
            recipient_contact=form.recipient_contact.data,
            
            # Cargo information
            cargo_name=form.cargo_name.data,
            cargo_description=form.cargo_description.data,
            cargo_length=form.cargo_length.data,
            cargo_width=form.cargo_width.data,
            cargo_height=form.cargo_height.data,
            cargo_weight=form.cargo_weight.data
        )
        
        # Save to database
        db.session.add(shipment)
        db.session.commit()
        
        # Add initial tracking entry
        shipment.add_tracking_update("Booking Received", "Shipment booking has been received and is being processed.")
        
        # Send email notification
        send_booking_email(shipment)
        
        # Redirect to confirmation page
        return redirect(url_for('shipment.booking_confirmation', tracking_number=shipment.tracking_number))
    
    return render_template('book.html', form=form)

@shipment_bp.route('/booking-confirmation/<tracking_number>')
def booking_confirmation(tracking_number):
    # Get shipment by tracking number
    shipment = Shipment.query.filter_by(tracking_number=tracking_number).first_or_404()
    
    return render_template('booking_confirmation.html', shipment=shipment)
