from app import db, login_manager
from datetime import datetime
from flask_login import UserMixin
import uuid

# Function to generate tracking numbers
def generate_tracking_number():
    return f"BX{uuid.uuid4().hex[:8].upper()}"

class Admin(UserMixin, db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(64), unique=True, nullable=False)
    password_hash = db.Column(db.String(256), nullable=False)

@login_manager.user_loader
def load_user(user_id):
    return Admin.query.get(int(user_id))

class Shipment(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    tracking_number = db.Column(db.String(16), unique=True, default=generate_tracking_number)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    
    # Sender information
    sender_name = db.Column(db.String(100), nullable=False)
    sender_email = db.Column(db.String(100), nullable=False)
    sender_address = db.Column(db.Text, nullable=False)
    sender_contact = db.Column(db.String(20), nullable=False)
    
    # Recipient information
    recipient_name = db.Column(db.String(100), nullable=False)
    recipient_email = db.Column(db.String(100), nullable=False)
    recipient_address = db.Column(db.Text, nullable=False)
    recipient_contact = db.Column(db.String(20), nullable=False)
    
    # Cargo information
    cargo_name = db.Column(db.String(100), nullable=False)
    cargo_description = db.Column(db.Text, nullable=False)
    cargo_length = db.Column(db.Float, nullable=True)
    cargo_width = db.Column(db.Float, nullable=True)
    cargo_height = db.Column(db.Float, nullable=True)
    cargo_weight = db.Column(db.Float, nullable=True)
    
    # Current status
    current_status = db.Column(db.String(50), default="Booking Received")
    
    # Relationship to tracking history
    tracking_history = db.relationship('TrackingHistory', backref='shipment', lazy=True, cascade="all, delete-orphan")
    
    def __repr__(self):
        return f"<Shipment {self.tracking_number}>"
    
    def add_tracking_update(self, status, notes):
        """Add a tracking update to this shipment"""
        update = TrackingHistory(
            shipment_id=self.id,
            status=status,
            notes=notes
        )
        db.session.add(update)
        self.current_status = status
        db.session.commit()
        return update

class TrackingHistory(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    shipment_id = db.Column(db.Integer, db.ForeignKey('shipment.id'), nullable=False)
    timestamp = db.Column(db.DateTime, default=datetime.utcnow)
    status = db.Column(db.String(50), nullable=False)
    notes = db.Column(db.Text, nullable=True)
    
    def __repr__(self):
        return f"<TrackingHistory {self.status} at {self.timestamp}>"

class Contact(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False)
    email = db.Column(db.String(100), nullable=False)
    address = db.Column(db.Text, nullable=False)
    contact_number = db.Column(db.String(20), nullable=False)
    message = db.Column(db.Text, nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    
    def __repr__(self):
        return f"<Contact {self.name}>"
