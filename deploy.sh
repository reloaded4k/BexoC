#!/bin/bash
# Deployment script for Bexo Cargo application

# Configuration - Change these variables
REMOTE_USER="your-username"
REMOTE_HOST="your-server-ip"
REMOTE_PATH="/var/www/bexocargo"
APP_NAME="bexocargo"

# Display script header
echo "====================================="
echo "Bexo Cargo Deployment Script"
echo "====================================="
echo

# Create deployment package
echo "Creating deployment package..."
tar -czvf ${APP_NAME}.tar.gz --exclude="venv" --exclude="__pycache__" --exclude="*.pyc" --exclude=".git" .
echo "Package created: ${APP_NAME}.tar.gz"
echo

# Transfer package to remote server
echo "Transferring package to remote server..."
scp ${APP_NAME}.tar.gz ${REMOTE_USER}@${REMOTE_HOST}:~
echo "Package transferred successfully."
echo

# Execute deployment on remote server
echo "Executing deployment on remote server..."
ssh ${REMOTE_USER}@${REMOTE_HOST} << EOF
    echo "Stopping application service..."
    sudo systemctl stop ${APP_NAME} || echo "Service not running, continuing..."
    
    echo "Extracting application package..."
    sudo mkdir -p ${REMOTE_PATH}
    sudo tar -xzf ~/${APP_NAME}.tar.gz -C ${REMOTE_PATH}
    sudo chown -R www-data:www-data ${REMOTE_PATH}
    
    echo "Setting up Python environment..."
    cd ${REMOTE_PATH}
    [ -d "venv" ] || sudo -u www-data python3 -m venv venv
    sudo -u www-data venv/bin/pip install --upgrade pip
    sudo -u www-data venv/bin/pip install -r requirements.txt
    sudo -u www-data venv/bin/pip install gunicorn
    
    echo "Restarting application service..."
    sudo systemctl daemon-reload
    sudo systemctl restart ${APP_NAME}
    sudo systemctl restart nginx
    
    echo "Cleaning up..."
    rm ~/${APP_NAME}.tar.gz
EOF
echo "Deployment completed successfully!"
echo

# Clean up local deployment package
echo "Cleaning up local deployment package..."
rm ${APP_NAME}.tar.gz
echo "Done!"
echo

echo "====================================="
echo "Deployment completed successfully!"
echo "Visit https://your-domain.com to verify."
echo "====================================="