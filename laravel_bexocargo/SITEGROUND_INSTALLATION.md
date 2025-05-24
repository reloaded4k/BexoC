# Bexo Cargo Laravel - SiteGround Installation Guide

This guide will help you deploy the Laravel version of Bexo Cargo to SiteGround hosting.

## Prerequisites

- A SiteGround hosting account with:
  - PHP 8.1+ support
  - MySQL database
  - SSH access (optional but recommended)

## Step 1: Database Setup

1. Log in to your SiteGround Site Tools
2. Go to Site > MySQL
3. Create a new MySQL database:
   - Name: bexocargo_db (or your preferred name)
   - User: Create a new user with a strong password
4. Note down the database credentials:
   - Database name
   - Database username
   - Database password
   - Database host (usually localhost)

These credentials will be used in your .env file configuration.

## Step 2: Upload Application Files

1. Download the complete Laravel application
2. Using Site Tools > File Manager or FTP:
   - Navigate to your public_html folder (or a subdirectory if you want to install in a subdomain)
   - Upload all files to this directory

## Step 3: Configure Environment

1. Rename the `.env.example` file to `.env`
2. Edit the `.env` file to include your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

3. Set your application key by running:
   ```
   php artisan key:generate
   ```

4. Configure mail settings:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email@gmail.com
   MAIL_PASSWORD=your_app_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@bexocargo.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

## Step 4: Set Folder Permissions

```bash
chmod -R 755 storage bootstrap/cache
```

## Step 5: Run Migrations

Once you've properly configured your MySQL database connection in the .env file, run:

```bash
php artisan migrate
```

This will create all the necessary database tables in your MySQL database. If you encounter any issues with the migration, make sure your MySQL user has sufficient privileges to create tables and indexes.

## Step 6: Create Admin User

```bash
php artisan tinker
```

Then run:
```php
use App\Models\User;
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('your_secure_password'),
    'is_admin' => true
]);
```

## Step 7: SiteGround Specific Configuration

### PHP Configuration

1. Go to Site Tools > DevOps > PHP Manager
2. Ensure you're using PHP 8.1 or higher
3. Increase memory limit to at least 128M
4. Increase max execution time to 60 seconds

### URL Rewriting

Create or update the .htaccess file in your public directory with:

```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Laravel Scheduler (Optional)

If you need to run scheduled tasks, set up a cron job through SiteGround's Cron Jobs tool:

```
* * * * * php /home/username/public_html/path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

## Step 8: Test Your Application

1. Visit your website
2. Test the admin login functionality
3. Create a test shipment
4. Verify tracking functionality

## Troubleshooting

### Common Issues

1. **500 Server Error**: Check the Laravel log files in the storage/logs directory
2. **White Screen**: Increase PHP memory limit in SiteGround PHP Manager
3. **Database Connection Error**: Verify database credentials in .env file
4. **Permission Issues**: Make sure storage and bootstrap/cache directories are writable

### Getting Support

If you encounter any issues, check the Laravel documentation or contact SiteGround support.