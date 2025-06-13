# BexoCargo - Shipping & Parcel Tracking System

A comprehensive web application for international shipping and logistics management, designed specifically for African markets.

## Features

### Customer Portal
- Real-time shipment tracking
- Online booking system
- Invoice viewing and download
- Contact forms
- Mobile-optimized interface

### Admin Panel
- Complete shipment management
- Status tracking with history
- Invoice upload and management
- Customer communication tools
- Comprehensive dashboard

## Quick Start

1. **Database Setup**: Enable PostgreSQL in Replit Tools → Database
2. **Run Application**: Click the Run button
3. **Admin Access**: 
   - URL: `/admin/login`
   - Username: `admin`
   - Password: `admin123`

## Configuration

### Required Environment Variables
- `SESSION_SECRET`: Random string for Flask sessions
- `DATABASE_URL`: Automatically set by Replit PostgreSQL

### Optional Email Configuration
- `MAIL_SERVER`: SMTP server (default: smtp.gmail.com)
- `MAIL_USERNAME`: Email username
- `MAIL_PASSWORD`: Email password

## Technology Stack

- **Backend**: Python Flask
- **Database**: PostgreSQL
- **Frontend**: Bootstrap 5, HTML5, CSS3
- **File Handling**: Secure invoice management
- **Authentication**: Flask-Login
- **Forms**: WTForms validation

## Security

- CSRF protection on all forms
- Secure file upload validation
- Password hashing with Werkzeug
- SQL injection protection via SQLAlchemy ORM
- Proper error handling and logging

## Mobile Compatibility

- Responsive Bootstrap design
- iOS-optimized PDF viewing
- Touch-friendly interface
- Cross-browser compatibility

## Support

For technical support or customization requests, contact the development team.

---

**Built with ❤️ for efficient logistics management**