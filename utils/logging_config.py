import logging
import os
from datetime import datetime
from flask import request, current_app
import traceback

def setup_logging(app):
    """Configure application-wide logging"""
    
    # Create logs directory if it doesn't exist
    log_dir = os.path.join(app.root_path, 'logs')
    os.makedirs(log_dir, exist_ok=True)
    
    # Configure logging format
    formatter = logging.Formatter(
        '[%(asctime)s] %(levelname)s in %(module)s: %(message)s'
    )
    
    # File handler for general application logs
    file_handler = logging.FileHandler(os.path.join(log_dir, 'app.log'))
    file_handler.setFormatter(formatter)
    file_handler.setLevel(logging.INFO)
    
    # File handler for error logs
    error_handler = logging.FileHandler(os.path.join(log_dir, 'errors.log'))
    error_handler.setFormatter(formatter)
    error_handler.setLevel(logging.ERROR)
    
    # Console handler for development
    console_handler = logging.StreamHandler()
    console_handler.setFormatter(formatter)
    console_handler.setLevel(logging.DEBUG if app.debug else logging.INFO)
    
    # Add handlers to app logger
    app.logger.addHandler(file_handler)
    app.logger.addHandler(error_handler)
    app.logger.addHandler(console_handler)
    app.logger.setLevel(logging.INFO)
    
    return app.logger

def log_error(error, context=None, user_id=None):
    """Log errors with context information"""
    try:
        error_info = {
            'timestamp': datetime.utcnow().isoformat(),
            'error_type': type(error).__name__,
            'error_message': str(error),
            'traceback': traceback.format_exc(),
            'url': request.url if request else None,
            'method': request.method if request else None,
            'ip_address': request.remote_addr if request else None,
            'user_agent': request.headers.get('User-Agent') if request else None,
            'user_id': user_id,
            'context': context
        }
        
        current_app.logger.error(f"Application Error: {error_info}")
        
    except Exception as logging_error:
        # Fallback if logging itself fails
        print(f"Logging failed: {logging_error}")
        print(f"Original error: {error}")

def log_info(message, context=None, user_id=None):
    """Log informational messages with context"""
    try:
        info = {
            'timestamp': datetime.utcnow().isoformat(),
            'message': message,
            'url': request.url if request else None,
            'user_id': user_id,
            'context': context
        }
        
        current_app.logger.info(f"Info: {info}")
        
    except Exception as logging_error:
        print(f"Info logging failed: {logging_error}")

def log_warning(message, context=None, user_id=None):
    """Log warning messages with context"""
    try:
        warning = {
            'timestamp': datetime.utcnow().isoformat(),
            'message': message,
            'url': request.url if request else None,
            'user_id': user_id,
            'context': context
        }
        
        current_app.logger.warning(f"Warning: {warning}")
        
    except Exception as logging_error:
        print(f"Warning logging failed: {logging_error}")