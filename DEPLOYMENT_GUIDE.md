# BexoCargo Deployment Guide

## Option 1: Direct Replit Transfer (Recommended)

### Step 1: Export from Current Replit
1. In your current Replit, click the three dots menu (⋯)
2. Select "Download as zip"
3. This downloads the entire project

### Step 2: Import to Client's Replit
1. Client logs into their Replit account
2. Click "Create Repl"
3. Select "Import from GitHub" or "Upload files"
4. Upload the downloaded zip file
5. Replit will automatically detect it's a Python Flask project

## Option 2: GitHub Repository Method

### Step 1: Create Repository
1. Create a new GitHub repository
2. Push this codebase to the repository
3. Make it public or give client access

### Step 2: Import to Client's Replit
1. Client clicks "Create Repl" 
2. Select "Import from GitHub"
3. Enter the repository URL
4. Replit imports and sets up automatically

## Option 3: Replit Teams/Multiplayer

### Share Direct Access
1. Click "Share" button in current Replit
2. Add client's email address
3. Give them "Can edit" permissions
4. They can then fork it to their own account

## Post-Transfer Setup Required

### 1. Database Setup
Client needs to enable PostgreSQL:
- Go to Tools → Database
- Enable PostgreSQL
- Database will be automatically configured

### 2. Environment Variables
Client should set these secrets:
- `SESSION_SECRET`: Any random string for Flask sessions
- `MAIL_SERVER`: SMTP server (optional, for email features)
- `MAIL_USERNAME`: Email username (optional)
- `MAIL_PASSWORD`: Email password (optional)

### 3. Admin Account
First run will create default admin:
- Username: `admin`
- Password: `admin123`
- Client should change this immediately

### 4. File Structure
Ensure these directories exist:
- `static/invoices/` (for invoice uploads)
- `logs/` (for application logs)

## Running the Application

1. Replit should auto-detect and run with: `gunicorn --bind 0.0.0.0:5000 --reuse-port --reload main:app`
2. If not, client can click "Run" button
3. Application will be available at the Replit URL

## Important Notes

- All uploaded invoices will need to be re-uploaded
- Database will be empty initially
- Email functionality requires SMTP configuration
- SSL/HTTPS is handled automatically by Replit