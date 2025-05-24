"""
AWS Deployment Helper for Bexo Cargo application.
This script helps configure your application for AWS Elastic Beanstalk deployment.
"""
import os

# Create AWS Elastic Beanstalk configuration
if not os.path.exists('.ebextensions'):
    os.makedirs('.ebextensions')

# Create config file for Python settings
with open('.ebextensions/01_python.config', 'w') as f:
    f.write("""option_settings:
  aws:elasticbeanstalk:container:python:
    WSGIPath: wsgi.py
  aws:elasticbeanstalk:application:environment:
    FLASK_ENV: production
""")

# Create config file for database
with open('.ebextensions/02_database.config', 'w') as f:
    f.write("""Resources:
  AWSEBRDSDatabase:
    Type: AWS::RDS::DBInstance
    Properties:
      Engine: postgres
      DBName: bexocargo
      MasterUsername: bexocargo
      MasterUserPassword: PASSWORD_PLACEHOLDER
      DBInstanceClass: db.t3.micro
      AllocatedStorage: 5
      StorageType: gp2
      MultiAZ: false
      DBParameterGroupName: default.postgres12
      VPCSecurityGroups:
        - Fn::GetAtt: [AWSEBSecurityGroup, GroupId]
      
option_settings:
  aws:elasticbeanstalk:application:environment:
    DATABASE_URL: '`{"Fn::Join":["", ["postgresql://", {"Ref":"AWSEBRDSDatabase.MasterUsername"}, ":", {"Ref":"AWSEBRDSDatabase.MasterUserPassword"}, "@", {"Fn::GetAtt":["AWSEBRDSDatabase", "Endpoint.Address"]}, ":", {"Fn::GetAtt":["AWSEBRDSDatabase", "Endpoint.Port"]}, "/", {"Ref":"AWSEBRDSDatabase.DBName"}]]}`'
""")

# Create Procfile
with open('Procfile', 'w') as f:
    f.write("web: gunicorn --bind 0.0.0.0:8080 --workers=3 wsgi:application")

# Create .ebignore
with open('.ebignore', 'w') as f:
    f.write("""
__pycache__/
*.py[cod]
*$py.class
*.so
.Python
env/
build/
develop-eggs/
dist/
downloads/
eggs/
.eggs/
lib/
lib64/
parts/
sdist/
var/
*.egg-info/
.installed.cfg
*.egg
*.manifest
*.spec
.git
.gitignore
.env
venv/
.vscode/
.idea/
.DS_Store
""")

# Create AWS deployment guide
with open('aws_deployment_guide.md', 'w') as f:
    f.write("""# Bexo Cargo - AWS Deployment Guide

This guide will help you deploy the Bexo Cargo application to AWS Elastic Beanstalk.

## Prerequisites

1. Install the AWS CLI and EB CLI:
   ```
   pip install awscli awsebcli
   ```

2. Configure AWS credentials:
   ```
   aws configure
   ```

## Deployment Steps

### 1. Initialize Elastic Beanstalk

```bash
eb init -p python-3.8 bexo-cargo --region us-east-1
```

Replace `us-east-1` with your preferred AWS region.

### 2. Configure Security

Before creating the environment, update the database password in the `.ebextensions/02_database.config` file:

Replace `PASSWORD_PLACEHOLDER` with a secure password.

### 3. Create Environment

```bash
eb create bexo-cargo-production
```

This will:
- Create an Elastic Beanstalk environment
- Set up a PostgreSQL RDS database
- Configure security groups
- Deploy your application

### 4. Set Environment Variables

You can set additional environment variables:

```bash
eb setenv SESSION_SECRET=your_secure_session_key ADMIN_USERNAME=admin ADMIN_PASSWORD=secure_password MAIL_SERVER=smtp.gmail.com MAIL_PORT=587 MAIL_USE_TLS=True MAIL_USERNAME=your_email@gmail.com MAIL_PASSWORD=your_app_password MAIL_DEFAULT_SENDER=noreply@bexocargo.com
```

### 5. Open the Application

```bash
eb open
```

### 6. Initialize the Database

SSH into the instance and run the database initialization:

```bash
eb ssh
cd /var/app/current
source /var/app/venv/*/bin/activate
python -c "from app import app, db; from models import Admin; from werkzeug.security import generate_password_hash; with app.app_context(): db.create_all(); admin = Admin.query.filter_by(username='admin').first(); if not admin: admin = Admin(username='admin', password_hash=generate_password_hash('admin123')); db.session.add(admin); db.session.commit();"
```

### 7. Monitor Your Application

```bash
eb logs
eb status
```

### 8. Update Your Application

When you make changes, deploy them with:

```bash
eb deploy
```

## Troubleshooting

- **Database Connection Issues**: Verify RDS security group allows connections from your EB environment
- **Application Errors**: Check logs with `eb logs`
- **Deployment Failures**: Use `eb deploy --verbose` for detailed logs

## Cost Management

AWS Elastic Beanstalk with a t3.micro instance and RDS db.t3.micro database will cost approximately $30-40 per month. Set up AWS Budgets to monitor costs.

## Security Best Practices

1. Store sensitive credentials in AWS Parameter Store or Secrets Manager
2. Enable HTTPS using ACM (AWS Certificate Manager)
3. Set up CloudWatch alarms for monitoring
4. Regularly update your application and dependencies

""")

print("AWS deployment files created successfully.")