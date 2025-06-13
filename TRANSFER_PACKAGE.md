# Quick Transfer Instructions

## Method 1: Download & Upload (Easiest)

**From your account:**
1. Click three dots (⋯) → "Download as zip" 
2. Send zip file to client

**Client setup:**
1. Create new Repl → "Import from zip"
2. Upload the zip file
3. Enable Database (Tools → Database → PostgreSQL)
4. Click Run

## Method 2: GitHub Transfer

**Create repository with this code:**
```bash
# If you want to push to GitHub first
git init
git add .
git commit -m "BexoCargo shipping application"
git remote add origin [YOUR_REPO_URL]
git push -u origin main
```

**Client imports:**
- New Repl → Import from GitHub → Enter repo URL

## Method 3: Direct Share

1. Click "Share" in current Repl
2. Add client email with "Can edit" 
3. Client can fork to their account

---

**Post-transfer:** Client needs to set one secret:
- `SESSION_SECRET` = any random string

**Login:** admin / admin123

**Ready to use immediately!**