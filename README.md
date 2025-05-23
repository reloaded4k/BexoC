# Bexo Cargo - Shipping and Parcel Tracking Web Application

A global shipping and logistics web application providing comprehensive parcel tracking and booking services, with a focus on simplifying international shipping for African markets.

## Features

- Comprehensive shipment booking system
- Real-time parcel tracking
- Admin dashboard for shipment management
- Email notifications for booking confirmations and status updates
- Specialized for African international shipping routes
- Responsive design for all devices

## Deployment Guide

### Minimum Server Requirements

- **Operating System**: Linux (Ubuntu 20.04+ or similar distribution)
- **Python Version**: Python 3.8+
- **Database**: PostgreSQL 12+
- **Web Server**: Nginx (for production) + Gunicorn
- **Memory**: Minimum 1GB RAM
- **Storage**: At least 10GB available disk space
- **Network**: Public IP address or domain name for public access

### Folder Structure

```
bexocargo/
├── app.py                 # Main application setup
├── config.py              # Configuration settings
├── forms.py               # Form definitions
├── main.py                # Entry point
├── models.py              # Database models
├── README.md              # Documentation
├── requirements.txt       # Python dependencies
├── wsgi.py                # WSGI entry point for Gunicorn
├── .env.example           # Example environment variables
├── routes/                # Route handlers
│   ├── __init__.py
│   ├── admin.py
│   ├── main.py
│   └── shipment.py
├── static/                # Static assets
│   ├── css/
│   ├── images/
│   └── js/
├── templates/             # HTML templates
│   ├── admin/
│   └── pages/
└── utils/                 # Utility functions
    ├── __init__.py
    ├── email.py
    └── tracking.py
```

### Installation and Setup Instructions

#### 1. Prepare the server

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required system dependencies
sudo apt install -y python3 python3-pip python3-venv postgresql nginx
```

#### 2. Set up PostgreSQL

```bash
# Install PostgreSQL if not included earlier
sudo apt install -y postgresql postgresql-contrib

# Create database and user
sudo -u postgres psql -c "CREATE USER bexocargo WITH PASSWORD 'secure_password';"
sudo -u postgres psql -c "CREATE DATABASE bexocargo_db OWNER bexocargo;"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE bexocargo_db TO bexocargo;"
```

#### 3. Clone/extract the application

```bash
# Create directory for the application
mkdir -p /var/www/bexocargo

# Extract application (if using tarball)
tar -xzf bexocargo.tar.gz -C /var/www/bexocargo

# Or clone from Git repository
# git clone https://github.com/your-org/bexocargo.git /var/www/bexocargo

# Set appropriate permissions
sudo chown -R www-data:www-data /var/www/bexocargo
```

#### 4. Set up Python environment

```bash
# Create and activate virtual environment
cd /var/www/bexocargo
python3 -m venv venv
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt
pip install gunicorn  # If not in requirements.txt
```

#### 5. Configure environment variables

```bash
# Create .env file from example
cp .env.example .env

# Edit environment variables
nano .env
```

#### 6. Initialize the database

```bash
# Create the initial database tables
export FLASK_APP=main.py
flask shell
# In the shell:
from app import db
db.create_all()
exit()
```

### System/Environment Configuration Setup

#### 1. Create a `.env` file with the following variables:

```
# Database configuration
DATABASE_URL=postgresql://bexocargo:secure_password@localhost/bexocargo_db

# Flask configuration
SESSION_SECRET=your_secure_secret_key
FLASK_ENV=production

# Email configuration
MAIL_SERVER=smtp.gmail.com
MAIL_PORT=587
MAIL_USE_TLS=True
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_email_password
MAIL_DEFAULT_SENDER=noreply@bexocargo.com

# Admin configuration
ADMIN_EMAIL=admin@bexocargo.com
```

#### 2. Set up Gunicorn service

Create a systemd service file at `/etc/systemd/system/bexocargo.service`:

```ini
[Unit]
Description=Bexo Cargo Web Application
After=network.target postgresql.service

[Service]
User=www-data
Group=www-data
WorkingDirectory=/var/www/bexocargo
Environment="PATH=/var/www/bexocargo/venv/bin"
EnvironmentFile=/var/www/bexocargo/.env
ExecStart=/var/www/bexocargo/venv/bin/gunicorn --workers 3 --bind 0.0.0.0:8000 --timeout 120 wsgi:app

[Install]
WantedBy=multi-user.target
```

#### 3. Set up Nginx configuration

Create a file at `/etc/nginx/sites-available/bexocargo`:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;

    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    location /static {
        alias /var/www/bexocargo/static;
    }
}
```

#### 4. Enable and start services

```bash
# Enable Nginx site
sudo ln -s /etc/nginx/sites-available/bexocargo /etc/nginx/sites-enabled/

# Test Nginx configuration
sudo nginx -t

# Enable and start services
sudo systemctl enable bexocargo
sudo systemctl start bexocargo
sudo systemctl enable nginx
sudo systemctl restart nginx
```

### Bundling/Export Method

#### Option 1: Tarball Package

1. Create a `requirements.txt` file if not already present:

```bash
pip freeze > requirements.txt
```

2. Create a tarball excluding unnecessary files:

```bash
tar -czvf bexocargo.tar.gz --exclude="venv" --exclude="__pycache__" --exclude="*.pyc" --exclude=".git" .
```

#### Option 2: Git Repository (recommended)

1. Initialize git repository if not already:

```bash
git init
```

2. Create proper `.gitignore` file:

```
# .gitignore
venv/
__pycache__/
*.py[cod]
.env
*.db
```

3. Commit all changes and push to a remote repository:

```bash
git add .
git commit -m "Initial deployment version"
git remote add origin https://github.com/your-org/bexocargo.git
git push -u origin main
```

#### Option 3: Docker Container

1. Create a `Dockerfile`:

```dockerfile
FROM python:3.9-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

EXPOSE 5000

CMD ["gunicorn", "--bind", "0.0.0.0:5000", "wsgi:app"]
```

2. Create a `docker-compose.yml` for easier deployment:

```yaml
version: '3'

services:
  web:
    build: .
    ports:
      - "5000:5000"
    env_file:
      - .env
    depends_on:
      - db
  
  db:
    image: postgres:12
    volumes:
      - postgres_data:/var/lib/postgresql/data/
    environment:
      - POSTGRES_USER=bexocargo
      - POSTGRES_PASSWORD=secure_password
      - POSTGRES_DB=bexocargo_db

volumes:
  postgres_data:
```

### Options for Ongoing Deployment/Updates

#### 1. Git-based CI/CD

1. Set up a CI/CD pipeline using GitHub Actions or GitLab CI/CD
2. Create a workflow that on push to main branch:
   - Runs tests
   - Builds the application
   - Deploys to the production server

Example GitHub Action workflow (`.github/workflows/deploy.yml`):

```yaml
name: Deploy

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    
    - name: Set up Python
      uses: actions/setup-python@v2
      with:
        python-version: '3.9'
    
    - name: Install dependencies
      run: |
        python -m pip install --upgrade pip
        if [ -f requirements.txt ]; then pip install -r requirements.txt; fi
    
    - name: Deploy to server
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/bexocargo
          git pull
          source venv/bin/activate
          pip install -r requirements.txt
          systemctl restart bexocargo
```

#### 2. Direct transfer from Replit

1. Create a deployment script in Replit that:
   - Creates a tarball of the application
   - Transfers it to the production server using SCP
   - Runs remote commands to extract and update the application

#### 3. Manual deployment with proper documentation

1. Document the deployment process step by step
2. Include rollback procedures in case of issues
3. Create a checklist for each deployment

## Maintenance and Troubleshooting

### Logging

Logs for the application are available in the following locations:

- Gunicorn logs: `/var/log/bexocargo/gunicorn.log`
- Application logs: `/var/log/bexocargo/app.log`
- Nginx access logs: `/var/log/nginx/access.log`
- Nginx error logs: `/var/log/nginx/error.log`

### Common Issues and Solutions

#### Application not starting

Check the Gunicorn logs for any errors:

```bash
sudo journalctl -u bexocargo
```

#### Database connection issues

Verify that PostgreSQL is running and the credentials are correct:

```bash
sudo systemctl status postgresql
psql -U bexocargo -h localhost -d bexocargo_db
```

#### Permission issues

Check and fix permissions for the application directory:

```bash
sudo chown -R www-data:www-data /var/www/bexocargo
sudo chmod -R 755 /var/www/bexocargo
```

### Backup and Restore

#### Database Backup

```bash
pg_dump -U bexocargo -d bexocargo_db > bexocargo_backup_$(date +\%Y\%m\%d).sql
```

#### Database Restore

```bash
psql -U bexocargo -d bexocargo_db < backup_file.sql
```

## Security Considerations

1. Use strong passwords for database and admin users
2. Keep the server updated with security patches
3. Set up SSL/TLS certificates for secure HTTPS connections
4. Consider implementing rate limiting to prevent abuse
5. Regularly backup your database and application files
6. Use a firewall to restrict access to only necessary ports

## Contact and Support

For assistance with deployment or any issues, please contact our technical support team at:

- Email: support@bexocargo.com
- Phone: +1-234-567-8910

## License

This software is proprietary and confidential. Unauthorized copying, distribution, modification, or use of this software is strictly prohibited.

© 2025 Bexo Cargo. All rights reserved.