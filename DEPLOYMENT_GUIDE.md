# Laravel 7 Fresh Installation - Deployment Guide

## ✅ Installation Complete

Your Laravel 7 application has been freshly installed with **exact same composer.json** as your working Laravel 7 app on the server.

### Installed Packages (Matching Working App Exactly):

-   ✅ Laravel Framework: `7.0.*`
-   ✅ Laratrust: `5.2.*`
-   ✅ Sanctum: `^2.15`
-   ✅ ramsey/uuid: `3.9.7` (PHP 7.2-7.4 compatible) ✅
-   ✅ Doctrine DBAL: `^2.9`
-   ✅ Intervention Image: `^2.4`
-   ✅ PHPMailer: `^6.7`
-   ✅ Laravel Helpers: `^1.5`
-   ✅ All packages match working app versions exactly

## 📦 What Has Been Migrated

✅ **All Application Code:**

-   149 PHP files migrated from `onlinejobs-backend`
-   All Models (HasFactory trait removed for L7 compatibility)
-   All Controllers (Api, AppApi, Auth, etc.)
-   All Resources (34 API resource classes)
-   All Middleware
-   All Routes (`routes/api.php`)
-   All Migrations
-   Config files (sanctum, laratrust, captcha, image, cors)
-   Helpers.php file

✅ **Laravel 7 Configuration:**

-   Kernel.php updated with Sanctum middleware
-   RouteServiceProvider configured for L7
-   config/app.php updated with Intervention Image
-   Production-ready index.php for split directory

## 🚀 Deployment Steps

### Step 1: Upload Files

**Via cPanel File Manager or FTP:**

1. **Compress the entire `laravel-7` folder** on your local machine:

    ```bash
    cd /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7
    tar -czf laravel-7.tar.gz laravel-7/
    ```

    Or use zip:

    ```bash
    zip -r laravel-7.zip laravel-7/
    ```

2. **Upload to server:**
    - Upload `laravel-7.tar.gz` or `laravel-7.zip` to `/home/no47agyrt0nt/`
    - Extract it
    - **Rename** the extracted folder from `laravel-7` to `lv_mobile_app_api` (or keep existing name if already correct)

### Step 2: Configure Public Directory

**Your setup:**

-   Public files: `/home/no47agyrt0nt/public_html/mobile-api/`
-   Laravel app: `/home/no47agyrt0nt/lv_mobile_app_api/`

**The `index.php` is already configured** in `public/index.php` with the correct path:

```php
define('LARAVEL_APP_PATH', '/home/no47agyrt0nt/lv_mobile_app_api');
```

**Copy `public/index.php` to server:**

-   Copy `laravel-7/public/index.php` to `/public_html/mobile-api/index.php`

### Step 3: Configure .env File

1. Navigate to `/lv_mobile_app_api/`
2. Copy `.env.example` to `.env` (if not exists)
3. Update these values:

    ```env
    APP_NAME="Online Jobs API"
    APP_ENV=production
    APP_KEY=base64:YOUR_APP_KEY_HERE
    APP_DEBUG=false
    APP_URL=https://onlinejobs.my

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

4. **Generate APP_KEY** (if you have terminal access):

    ```bash
    cd /home/no47agyrt0nt/lv_mobile_app_api
    php artisan key:generate
    ```

    **Or manually:** Copy `APP_KEY` from your working Laravel 7 app's `.env` file.

### Step 4: Set File Permissions

**Via cPanel File Manager:**

1. Navigate to `/lv_mobile_app_api/`
2. Set permissions:
    - `storage/` folder: **775** (recursive)
    - `storage/logs/` folder: **775** (recursive)
    - `bootstrap/cache/` folder: **775** (recursive)

**How to set permissions:**

-   Right-click folder → **Change Permissions**
-   Set to **775** (rwxrwxr-x)
-   Check **Recurse into subdirectories**
-   Click **Change Permissions**

### Step 5: Verify Installation

1. **Test Laravel:**

    - Visit: `https://onlinejobs.my/mobile-api/`
    - Should load without errors

2. **Test API endpoint:**
    - Visit: `https://onlinejobs.my/mobile-api/api/countries` (or any API route)
    - Should return JSON response

## ✅ Verification Checklist

-   [ ] All files uploaded to `/lv_mobile_app_api/`
-   [ ] `vendor/` folder exists and contains packages
-   [ ] `index.php` copied to `/public_html/mobile-api/`
-   [ ] `.env` file configured with database credentials
-   [ ] `APP_KEY` set in `.env`
-   [ ] `storage/` folder permissions set to 775
-   [ ] `bootstrap/cache/` folder permissions set to 775
-   [ ] Application loads without errors

## 🔧 Troubleshooting

### If you get "500 Internal Server Error":

1. **Check Laravel logs:**

    - `/lv_mobile_app_api/storage/logs/laravel.log`
    - Look for the actual error message

2. **Verify paths in `index.php`:**

    - Make sure `LARAVEL_APP_PATH` points to correct location
    - Verify `vendor/autoload.php` exists

3. **Check file permissions:**
    - Storage and cache folders must be writable

### If you get "Vendor folder missing":

-   Make sure you uploaded the entire `vendor/` folder
-   Check that `vendor/autoload.php` exists

### If you get database errors:

-   Verify `.env` database credentials
-   Check database connection from cPanel

## 📝 Notes

-   **No terminal access needed** - Everything can be done via cPanel File Manager
-   **Vendor folder is pre-installed** - No need to run `composer install` on server
-   **Package versions match working app exactly** - Should work identically
-   **ramsey/uuid 3.9.7** - Compatible with PHP 7.2-7.4
-   **Fresh installation** - Clean Laravel 7 base with all your code migrated

## 🎉 Success!

Once deployed, your Laravel 7 API should work perfectly with your React Native mobile app!
