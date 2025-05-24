from flask import render_template, current_app
from flask_mail import Message
from app import mail
import logging

def send_email(subject, recipients, text_body, html_body, sender=None):
    """
    Send an email using Flask-Mail
    """
    try:
        msg = Message(subject, recipients=recipients, sender=sender)
        msg.body = text_body
        msg.html = html_body
        mail.send(msg)
        logging.info(f"Email sent successfully to {recipients}")
        return True
    except Exception as e:
        logging.error(f"Failed to send email: {e}")
        return False

def send_contact_email(contact):
    """
    Send email for contact form submissions
    """
    subject = f"New Contact Request from {contact.name}"
    recipients = [current_app.config['ADMIN_EMAIL']]
    
    # Create email body
    text_body = f"""
    New Contact Request
    
    Name: {contact.name}
    Email: {contact.email}
    Address: {contact.address}
    Contact Number: {contact.contact_number}
    
    Message:
    {contact.message}
    """
    
    html_body = f"""
    <h2>New Contact Request</h2>
    <p><strong>Name:</strong> {contact.name}</p>
    <p><strong>Email:</strong> {contact.email}</p>
    <p><strong>Address:</strong> {contact.address}</p>
    <p><strong>Contact Number:</strong> {contact.contact_number}</p>
    <h3>Message:</h3>
    <p>{contact.message}</p>
    """
    
    return send_email(subject, recipients, text_body, html_body)

def send_booking_email(shipment):
    """
    Send email for shipment bookings
    """
    subject = f"New Shipment Booking - {shipment.tracking_number}"
    recipients = [current_app.config['ADMIN_EMAIL']]
    
    # Create email body
    text_body = f"""
    New Shipment Booking - {shipment.tracking_number}
    
    Sender Information:
    Name: {shipment.sender_name}
    Email: {shipment.sender_email}
    Address: {shipment.sender_address}
    Contact: {shipment.sender_contact}
    
    Recipient Information:
    Name: {shipment.recipient_name}
    Email: {shipment.recipient_email}
    Address: {shipment.recipient_address}
    Contact: {shipment.recipient_contact}
    
    Cargo Information:
    Name: {shipment.cargo_name}
    Description: {shipment.cargo_description}
    Dimensions: {shipment.cargo_length or 'N/A'} x {shipment.cargo_width or 'N/A'} x {shipment.cargo_height or 'N/A'} cm
    Weight: {shipment.cargo_weight or 'N/A'} kg
    
    Status: {shipment.current_status}
    """
    
    html_body = f"""
    <h2>New Shipment Booking - {shipment.tracking_number}</h2>
    
    <h3>Sender Information:</h3>
    <p><strong>Name:</strong> {shipment.sender_name}</p>
    <p><strong>Email:</strong> {shipment.sender_email}</p>
    <p><strong>Address:</strong> {shipment.sender_address}</p>
    <p><strong>Contact:</strong> {shipment.sender_contact}</p>
    
    <h3>Recipient Information:</h3>
    <p><strong>Name:</strong> {shipment.recipient_name}</p>
    <p><strong>Email:</strong> {shipment.recipient_email}</p>
    <p><strong>Address:</strong> {shipment.recipient_address}</p>
    <p><strong>Contact:</strong> {shipment.recipient_contact}</p>
    
    <h3>Cargo Information:</h3>
    <p><strong>Name:</strong> {shipment.cargo_name}</p>
    <p><strong>Description:</strong> {shipment.cargo_description}</p>
    <p><strong>Dimensions:</strong> {shipment.cargo_length or 'N/A'} x {shipment.cargo_width or 'N/A'} x {shipment.cargo_height or 'N/A'} cm</p>
    <p><strong>Weight:</strong> {shipment.cargo_weight or 'N/A'} kg</p>
    
    <p><strong>Status:</strong> {shipment.current_status}</p>
    """
    
    return send_email(subject, recipients, text_body, html_body)
