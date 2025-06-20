import os
from flask import Flask
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy.orm import DeclarativeBase
from flask_login import LoginManager
from flask_mail import Mail
from werkzeug.middleware.proxy_fix import ProxyFix
import logging
from utils.logging_config import setup_logging

# Configure logging
logging.basicConfig(level=logging.DEBUG)

# Create SQLAlchemy base class
class Base(DeclarativeBase):
    pass

# Initialize extensions
db = SQLAlchemy(model_class=Base)
login_manager = LoginManager()
mail = Mail()

def create_app():
    # Create Flask app
    app = Flask(__name__)
    
    # Configure app from config.py
    app.config.from_object('config.Config')
    
    # Set secret key
    app.secret_key = os.environ.get("SESSION_SECRET", "fal8j3k2nfa9032nkfds9023n2k3l")
    
    # Configure proxy for SSL
    app.wsgi_app = ProxyFix(app.wsgi_app, x_proto=1, x_host=1)
    
    # Initialize extensions with app
    db.init_app(app)
    login_manager.init_app(app)
    mail.init_app(app)
    
    # Setup comprehensive logging
    setup_logging(app)
    
    # Set login view
    login_manager.login_view = 'admin.login'
    
    with app.app_context():
        # Import models
        import models
        
        # Create all tables
        db.create_all()
        
        # Create admin account if it doesn't exist
        create_admin()
        
        # Register blueprints
        from routes.main import main_bp
        from routes.admin import admin_bp
        from routes.shipment import shipment_bp
        
        app.register_blueprint(main_bp)
        app.register_blueprint(admin_bp, url_prefix='/admin')
        app.register_blueprint(shipment_bp)
        
        # Register global error handlers
        register_error_handlers(app)
        
        return app

def register_error_handlers(app):
    """Register global error handlers for the application"""
    from flask import render_template, request, jsonify
    
    @app.errorhandler(404)
    def not_found_error(error):
        try:
            # Try to log the error safely
            app.logger.warning(f"404 Error: {request.url}")
            return render_template('404.html'), 404
        except Exception:
            # Fallback for template errors
            return '<h1>Page Not Found</h1><p>The requested page could not be found.</p>', 404
    
    @app.errorhandler(500)
    def internal_error(error):
        try:
            # Try to log the error safely
            app.logger.error(f"500 Error: {str(error)}")
            db.session.rollback()
            return render_template('500.html'), 500
        except Exception:
            # Fallback for template errors
            return '<h1>Server Error</h1><p>An internal server error occurred.</p>', 500
    
    @app.errorhandler(Exception)
    def handle_exception(error):
        try:
            # Try to log the error safely
            app.logger.error(f"Unhandled Exception: {type(error).__name__}: {str(error)}")
            db.session.rollback()
            return render_template('500.html'), 500
        except Exception:
            # Fallback for any errors in error handling
            return '<h1>Server Error</h1><p>An unexpected error occurred.</p>', 500

def create_admin():
    from models import Admin
    from werkzeug.security import generate_password_hash
    
    # Check if admin exists
    admin_exists = Admin.query.filter_by(username='admin').first()
    
    if not admin_exists:
        # Get admin credentials from environment variables or use defaults
        username = os.environ.get('ADMIN_USERNAME', 'admin')
        password = os.environ.get('ADMIN_PASSWORD', 'admin123')
        
        # Create admin account
        admin = Admin(
            username=username,
            password_hash=generate_password_hash(password)
        )
        
        # Add to database
        db.session.add(admin)
        db.session.commit()
        logging.info("Admin account created successfully")

# Create app
app = create_app()
