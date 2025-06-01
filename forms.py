from flask_wtf import FlaskForm
from flask_wtf.file import FileField, FileAllowed
from wtforms import StringField, TextAreaField, FloatField, PasswordField, SelectField, SubmitField
from wtforms.validators import DataRequired, Email, Optional, Length

class ContactForm(FlaskForm):
    name = StringField('Name', validators=[DataRequired()])
    email = StringField('Email', validators=[DataRequired(), Email()])
    address = TextAreaField('Address', validators=[DataRequired()])
    contact_number = StringField('Contact Number', validators=[DataRequired()])
    message = TextAreaField('Message', validators=[DataRequired()])

class ShipmentForm(FlaskForm):
    # Sender information
    sender_name = StringField('Sender Name', validators=[DataRequired()])
    sender_email = StringField('Sender Email', validators=[DataRequired(), Email()])
    sender_address = TextAreaField('Sender Address', validators=[DataRequired()])
    sender_contact = StringField('Sender Contact Number', validators=[DataRequired()])
    
    # Recipient information
    recipient_name = StringField('Recipient Name', validators=[DataRequired()])
    recipient_email = StringField('Recipient Email', validators=[DataRequired(), Email()])
    recipient_address = TextAreaField('Recipient Address', validators=[DataRequired()])
    recipient_contact = StringField('Recipient Contact Number', validators=[DataRequired()])
    
    # Cargo information
    cargo_name = StringField('Cargo Name', validators=[DataRequired()])
    cargo_description = TextAreaField('Cargo Description', validators=[DataRequired()])
    cargo_length = FloatField('Length (cm)', validators=[Optional()])
    cargo_width = FloatField('Width (cm)', validators=[Optional()])
    cargo_height = FloatField('Height (cm)', validators=[Optional()])
    cargo_weight = FloatField('Weight (kg)', validators=[Optional()])

class TrackingForm(FlaskForm):
    tracking_number = StringField('Tracking Number', validators=[DataRequired()])

class LoginForm(FlaskForm):
    username = StringField('Username', validators=[DataRequired()])
    password = PasswordField('Password', validators=[DataRequired()])

class TrackingUpdateForm(FlaskForm):
    status = SelectField('Status', choices=[
        ('Booking Received', 'Booking Received'),
        ('Processing', 'Processing'),
        ('Dispatched', 'Dispatched'),
        ('In Transit', 'In Transit'),
        ('Out For Delivery', 'Out For Delivery'),
        ('Custom Clearance Export', 'Custom Clearance Export'),
        ('Custom Clearance Import', 'Custom Clearance Import'),
        ('Shipment On Hold', 'Shipment On Hold'),
        ('Shipment Pending Release', 'Shipment Pending Release'),
        ('Shipment On Hold (Docs Request)', 'Shipment On Hold (Docs Request)'),
        ('Cancelled', 'Cancelled')
    ], validators=[DataRequired()])
    notes = TextAreaField('Notes', validators=[DataRequired()])

class EditShipmentForm(FlaskForm):
    # Sender information
    sender_name = StringField('Sender Name', validators=[DataRequired()])
    sender_email = StringField('Sender Email', validators=[DataRequired(), Email()])
    sender_address = TextAreaField('Sender Address', validators=[DataRequired()])
    sender_contact = StringField('Sender Contact Number', validators=[DataRequired()])
    
    # Recipient information
    recipient_name = StringField('Recipient Name', validators=[DataRequired()])
    recipient_email = StringField('Recipient Email', validators=[DataRequired(), Email()])
    recipient_address = TextAreaField('Recipient Address', validators=[DataRequired()])
    recipient_contact = StringField('Recipient Contact Number', validators=[DataRequired()])
    
    # Cargo information
    cargo_name = StringField('Cargo Name', validators=[DataRequired()])
    cargo_description = TextAreaField('Cargo Description', validators=[DataRequired()])
    cargo_length = FloatField('Length (cm)', validators=[Optional()])
    cargo_width = FloatField('Width (cm)', validators=[Optional()])
    cargo_height = FloatField('Height (cm)', validators=[Optional()])
    cargo_weight = FloatField('Weight (kg)', validators=[Optional()])
