# Bexo Cargo - Shipping and Parcel Tracking Web Application

A comprehensive logistics application for managing shipment bookings and parcel tracking with an admin backend. The app is built with Flask and follows clean architecture principles.

## Features

- **User-Facing Features**
  - Home page with service information, testimonials, and call-to-actions
  - Shipment booking with sender, recipient, and cargo details
  - Shipment tracking with detailed status updates
  - Contact form for customer inquiries
  - Static pages (About, Terms, Privacy, Shipping Terms)

- **Admin Panel Features**
  - Secure login with authentication
  - Dashboard with shipment statistics
  - Comprehensive shipment management
  - Status update system
  - Tracking history with timestamped notes
  - Shipment editing capabilities

## Tech Stack

- **Backend**
  - Flask (Web Framework)
  - Flask-SQLAlchemy (ORM)
  - Flask-Login (Authentication)
  - Flask-WTF (Form Handling)
  - Flask-Mail (Email Notifications)

- **Frontend**
  - Bootstrap 5 (Responsive UI Framework)
  - Font Awesome (Icons)
  - Custom CSS & JavaScript

- **Database**
  - SQLite (Development)
  - PostgreSQL (Production, optional)

## Installation

### Prerequisites

- Python 3.8+
- pip (Python package manager)

### Setup

1. **Clone the repository**

```bash
git clone https://github.com/yourusername/bexo-cargo.git
cd bexo-cargo
