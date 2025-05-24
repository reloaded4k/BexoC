# Bexo Cargo - Google Cloud Platform Deployment Guide

This guide will help you deploy the Bexo Cargo application to Google Cloud Platform using App Engine and Cloud SQL.

## Prerequisites

1. Install the Google Cloud SDK:
   - Visit https://cloud.google.com/sdk/docs/install

2. Initialize and authenticate:
   ```
   gcloud init
   gcloud auth login
   ```

3. Create a GCP project:
   ```
   gcloud projects create bexocargo-app
   gcloud config set project bexocargo-app
   ```

## Deployment Steps

### 1. Create a Cloud SQL PostgreSQL instance

```bash
gcloud sql instances create bexocargo-db \
  --database-version=POSTGRES_13 \
  --tier=db-f1-micro \
  --region=us-central1 \
  --root-password=YOUR_ROOT_PASSWORD \
  --storage-size=10GB
```

### 2. Create a database and user

```bash
gcloud sql databases create bexocargo --instance=bexocargo-db

gcloud sql users create bexocargo --instance=bexocargo-db \
  --password=YOUR_USER_PASSWORD
```

### 3. Get the connection information

```bash
gcloud sql instances describe bexocargo-db
```

Note the connectionName (it will be in the format: project:region:instance)

### 4. Update app.yaml with environment variables

Add the following environment variables to your app.yaml file:

```yaml
env_variables:
  DATABASE_URL: "postgresql+pg8000://bexocargo:YOUR_USER_PASSWORD@/bexocargo?unix_sock=/cloudsql/YOUR_CONNECTION_NAME/.s.PGSQL.5432"
  SESSION_SECRET: "your_secure_session_key"
  ADMIN_USERNAME: "admin"
  ADMIN_PASSWORD: "secure_password"
  MAIL_SERVER: "smtp.gmail.com"
  MAIL_PORT: "587"
  MAIL_USE_TLS: "True"
  MAIL_USERNAME: "your_email@gmail.com"
  MAIL_PASSWORD: "your_app_password"
  MAIL_DEFAULT_SENDER: "noreply@bexocargo.com"
```

Replace YOUR_CONNECTION_NAME with the connectionName you noted earlier.

### 5. Update requirements.txt

Make sure pg8000 is added to your requirements.txt:

```
pg8000==1.29.4
```

### 6. Deploy to App Engine

```bash
gcloud app deploy
```

### 7. Initialize the database

```bash
gcloud app instances ssh --service=default --version=YOUR_VERSION

# Once connected:
cd /srv/app
python -c "from app import app, db; from models import Admin; from werkzeug.security import generate_password_hash; with app.app_context(): db.create_all(); admin = Admin.query.filter_by(username='admin').first(); if not admin: admin = Admin(username='admin', password_hash=generate_password_hash('admin123')); db.session.add(admin); db.session.commit();"
```

### 8. Access your application

```bash
gcloud app browse
```

## Setting up Cloud SQL Proxy (for local development)

1. Download the Cloud SQL Proxy:
   https://cloud.google.com/sql/docs/postgres/connect-admin-proxy

2. Start the proxy:
   ```
   ./cloud_sql_proxy -instances=YOUR_CONNECTION_NAME=tcp:5432
   ```

3. Update your local .env file:
   ```
   DATABASE_URL=postgresql://bexocargo:YOUR_USER_PASSWORD@localhost:5432/bexocargo
   ```

## Using Secret Manager (recommended for production)

1. Store your secrets:
   ```
   echo -n "YOUR_SECRET_VALUE" | gcloud secrets create SECRET_NAME --data-file=-
   ```

2. Grant your App Engine service account access:
   ```
   gcloud secrets add-iam-policy-binding SECRET_NAME \
     --member=serviceAccount:YOUR_PROJECT_ID@appspot.gserviceaccount.com \
     --role=roles/secretmanager.secretAccessor
   ```

3. Update your app to access secrets:
   ```python
   from google.cloud import secretmanager

   client = secretmanager.SecretManagerServiceClient()
   name = f"projects/{project_id}/secrets/{secret_id}/versions/latest"
   response = client.access_secret_version(request={"name": name})
   secret_value = response.payload.data.decode("UTF-8")
   ```

## Cost Management

App Engine F2 instance and Cloud SQL db-f1-micro will cost approximately $25-35 per month. Set up GCP budgets to monitor costs.

## Security Best Practices

1. Use Secret Manager for all credentials
2. Set up IAM properly to limit access
3. Enable Cloud Audit Logging
4. Regularly update your application and dependencies
