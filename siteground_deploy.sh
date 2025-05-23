#!/bin/bash
# SiteGround Deployment Script for Bexo Cargo Application

# Display script header
echo "====================================="
echo "Bexo Cargo SiteGround Deployment Script"
echo "====================================="
echo

# Package directory name
PACKAGE_DIR="bexocargo_siteground"
PACKAGE_ZIP="bexocargo_siteground.zip"

# Create configuration
echo "Setting up SiteGround configuration..."

# Create siteground_setup.py file to help with initialization
cat > siteground_setup.py << 'EOL'
"""
SiteGround setup helper for Bexo Cargo application.
Run this file once after uploading to SiteGround to initialize the database.
"""
import os
import sys

try:
    from app import db
    print("Initializing database tables...")
    db.create_all()
    print("Database initialization successful!")
    
    print("\nSetting up admin user...")
    from models import Admin
    from werkzeug.security import generate_password_hash
    
    # Check if admin already exists
    from app import db
    admin = Admin.query.filter_by(username='admin').first()
    
    if admin:
        print("Admin user already exists.")
    else:
        # Create admin user with default credentials (change these immediately after setup)
        admin = Admin(
            username='admin',
            password_hash=generate_password_hash('admin123')
        )
        db.session.add(admin)
        db.session.commit()
        print("Default admin user created with username 'admin' and password 'admin123'")
        print("IMPORTANT: Login and change this password immediately!")
    
    print("\nBexo Cargo setup complete!")
    
except Exception as e:
    print(f"Error during setup: {e}")
    sys.exit(1)
EOL

# Create passenger_wsgi.py for SiteGround
cat > passenger_wsgi.py << 'EOL'
"""
Passenger WSGI entry point for SiteGround deployment.
"""
import os
import sys

# Add the application directory to Python path
sys.path.insert(0, os.path.dirname(__file__))

# Import the Flask application
from wsgi import app as application
EOL

# Create .htaccess for SiteGround
cat > .htaccess << 'EOL'
# SiteGround .htaccess for Python application
PassengerAppType wsgi
PassengerStartupFile passenger_wsgi.py
# Replace USERNAME with your actual SiteGround username in the final upload
PassengerAppRoot /home/USERNAME/public_html/bexocargo

# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
EOL

# Create wsgi.py if not exists
if [ ! -f "wsgi.py" ]; then
    cat > wsgi.py << 'EOL'
from main import app

if __name__ == "__main__":
    app.run()
EOL
fi

# Create siteground_install_guide.txt
cat > siteground_install_guide.txt << 'EOL'
BEXO CARGO - SITEGROUND INSTALLATION GUIDE
=========================================

This guide will help you set up Bexo Cargo on your SiteGround hosting account.

STEP 1: DATABASE SETUP
---------------------
1. Log in to your SiteGround Site Tools
2. Go to Site > MySQL/PostgreSQL
3. Create a new PostgreSQL database:
   - Name: bexocargo_db (or your preferred name)
   - User: Create a new user with a strong password
4. Note down the database credentials

STEP 2: UPLOAD APPLICATION FILES
-------------------------------
1. Extract the ZIP file you downloaded
2. Using Site Tools > File Manager or FTP:
   - Create a directory for the application (e.g., "bexocargo")
   - Upload all files to this directory

STEP 3: EDIT CONFIGURATION
-------------------------
1. Create a .env file in your application directory with the following content:
   ```
   DATABASE_URL=postgresql://username:password@localhost/bexocargo_db
   SESSION_SECRET=your_secure_secret_key
   FLASK_ENV=production
   MAIL_SERVER=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USE_TLS=True
   MAIL_USERNAME=your_email@gmail.com
   MAIL_PASSWORD=your_app_password
   MAIL_DEFAULT_SENDER=noreply@bexocargo.com
   ```

2. Edit the .htaccess file:
   - Replace USERNAME with your SiteGround username

STEP 4: SET UP PYTHON APPLICATION
--------------------------------
1. In Site Tools, go to DevOps > Python
2. Create a new Python application:
   - Python Version: 3.8+ 
   - Application Root: Your application directory
   - Application URL: Your domain or subdomain
   - Application Startup File: passenger_wsgi.py
   - Application Entry Point: application
3. Add all environment variables from your .env file

STEP 5: INSTALL DEPENDENCIES
--------------------------
1. Connect to your server via SSH
   - Set up SSH key in Site Tools > DevOps > SSH Keys Manager first
2. Run the following commands:
   ```
   cd ~/public_html/your-app-directory
   source python_app_venv/bin/activate
   pip install email-validator Flask Flask-Login Flask-Mail Flask-SQLAlchemy Flask-WTF gunicorn psycopg2-binary SQLAlchemy Werkzeug WTForms
   ```

STEP 6: INITIALIZE THE DATABASE
-----------------------------
1. While still connected via SSH and in the virtual environment:
   ```
   python siteground_setup.py
   ```
2. This will create database tables and set up a default admin user

STEP 7: SSL SETUP
---------------
1. In Site Tools > Security > SSL Manager
2. Enable Let's Encrypt SSL for your domain
3. Enable HTTPS Enforce

STEP 8: FINAL STEPS
-----------------
1. Restart your Python application in Site Tools > DevOps > Python
2. Visit your website to verify it's working
3. Login to the admin panel at /admin/login with:
   - Username: admin
   - Password: admin123
4. IMMEDIATELY change the admin password

TROUBLESHOOTING
--------------
- If you see errors, check Site Tools > Logs > Error Logs
- Verify database connection details are correct
- Make sure all environment variables are set
- Check file permissions (755 for directories, 644 for files)

For more assistance, contact support@bexocargo.com
EOL

# Create requirements list for reference
cat > requirements_list.txt << 'EOL'
# Required Python Packages for Bexo Cargo
# Install these via SiteGround's Python virtual environment

email-validator==2.0.0
Flask==2.3.3
Flask-Login==0.6.2
Flask-Mail==0.9.1
Flask-SQLAlchemy==3.1.1
Flask-WTF==1.2.1
gunicorn==23.0.0
psycopg2-binary==2.9.6
SQLAlchemy==2.0.19
Werkzeug==2.3.7
WTForms==3.0.1
EOL

# Prepare packaging directories
echo "Preparing package directory..."
mkdir -p $PACKAGE_DIR

# List of important files to copy
files_to_copy=(
    "app.py" 
    "config.py" 
    "forms.py" 
    "main.py" 
    "models.py" 
    "wsgi.py"
    "passenger_wsgi.py"
    ".htaccess"
    "siteground_setup.py"
    "siteground_install_guide.txt"
    "requirements_list.txt"
    ".env.example"
)

# Copy essential files
for file in "${files_to_copy[@]}"
do
    if [ -f "$file" ]; then
        cp "$file" "$PACKAGE_DIR/"
        echo "Copied $file"
    fi
done

# Copy directories
directories=(
    "routes"
    "static"
    "templates"
    "utils"
)

for dir in "${directories[@]}"
do
    if [ -d "$dir" ]; then
        cp -r "$dir" "$PACKAGE_DIR/"
        echo "Copied directory $dir"
    fi
done

# Clean up any unnecessary files
echo "Cleaning up package..."
find "$PACKAGE_DIR" -name "__pycache__" -type d -exec rm -rf {} +
find "$PACKAGE_DIR" -name "*.pyc" -delete
find "$PACKAGE_DIR" -name ".DS_Store" -delete

# Create ZIP archive
echo "Creating ZIP archive..."
zip -r "$PACKAGE_ZIP" "$PACKAGE_DIR"

# Cleanup
rm -rf "$PACKAGE_DIR"
rm passenger_wsgi.py
rm .htaccess
rm siteground_setup.py

echo "====================================="
echo "Deployment package created successfully!"
echo "Filename: $PACKAGE_ZIP"
echo ""
echo "Next steps:"
echo "1. Download $PACKAGE_ZIP from Replit"
echo "2. Follow instructions in siteground_install_guide.txt"
echo "3. Upload and configure on SiteGround"
echo "====================================="