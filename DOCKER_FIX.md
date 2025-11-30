# 🔧 Docker File Sharing Fix

## Issue
Docker Desktop needs permission to access your project folder.

## ✅ Solution

1. **Open Docker Desktop**
2. **Go to Settings** (gear icon)
3. **Click "Resources" → "File Sharing"**
4. **Click "+" to add a path**
5. **Add this path:**
   ```
   /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7
   ```
   Or add the parent directory:
   ```
   /Applications/XAMPP/xamppfiles/htdocs
   ```
6. **Click "Apply & Restart"**
7. **Wait for Docker to restart**

## After Fixing File Sharing

Run these commands:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7/laravel-7

# Start containers
docker compose up -d

# Install dependencies
docker compose exec app composer install --no-dev --optimize-autoloader

# Setup .env
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate

# Update .env database settings:
# DB_HOST=mysql
# DB_DATABASE=onlinejobs_db
# DB_USERNAME=onlinejobs_user
# DB_PASSWORD=root

# Set permissions
docker compose exec app chmod -R 775 storage bootstrap/cache

# Start Laravel server
docker compose exec app php artisan serve --host=0.0.0.0 --port=8000
```

## Current Status

✅ MySQL is running on port **3308**  
✅ phpMyAdmin is running on port **8080**  
⏳ App container waiting for file sharing to be configured

## Access

- **phpMyAdmin**: http://localhost:8080 (root/root)
- **Laravel API**: http://localhost:8000 (after setup)

