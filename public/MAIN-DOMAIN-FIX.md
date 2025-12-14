# Fix Main Domain (onlinejobs.my)

The error shows that the root `index.php` can't find the Laravel application in the `onlinejob2` directory.

## The Error

```
require(/home/no47agyrt0nt/public_html/../onlinejob2/vendor/autoload.php): failed to open stream
```

This means the root `index.php` is trying to load Laravel from `/home/no47agyrt0nt/onlinejob2/`, but the `vendor/autoload.php` file doesn't exist there.

## Solution Options

### Option 1: Fix the Root index.php (Recommended)

Replace the root `index.php` with the corrected version.

**File to update**: `/home/no47agyrt0nt/public_html/index.php`

**Use the file**: `index-root-FOR-MAIN-APP.php`

This file uses an **absolute path** to point to the main Laravel app, which is more reliable.

### Option 2: Use Router Version (If Mobile-API Routing Needed)

If you want both apps to work through the same root `index.php`, use the router version.

**File to use**: `index-root-ROUTER.php`

This automatically detects if the request is for `/mobile-api/` and routes accordingly.

## Step-by-Step Fix

### Step 1: Check if Main App Directory Exists

Verify the main Laravel application directory exists:

```bash
ls -la /home/no47agyrt0nt/onlinejob2/
ls -la /home/no47agyrt0nt/onlinejob2/vendor/autoload.php
```

**If the directory doesn't exist** or has a different name, you'll need to update the path in `index.php`.

### Step 2: Check if Vendor Directory Exists

If `vendor/autoload.php` doesn't exist, you need to run composer install:

```bash
cd /home/no47agyrt0nt/onlinejob2
composer install --no-dev --optimize-autoloader
```

### Step 3: Update Root index.php

**If using separate index.php files** (mobile-api has its own):

1. Backup current root index.php:

    ```bash
    cp /home/no47agyrt0nt/public_html/index.php /home/no47agyrt0nt/public_html/index.php.backup
    ```

2. Replace with: `index-root-FOR-MAIN-APP.php`

3. **Update the path** in the file if your main app is in a different location:
    ```php
    $laravelRoot = '/home/no47agyrt0nt/onlinejob2';
    ```

**If using router approach** (single index.php handles both):

1. Replace root index.php with: `index-root-ROUTER.php`
2. This will handle both main domain and mobile-api automatically

### Step 4: Verify Paths

Make sure these paths are correct in the `index.php` file:

-   **Main app path**: `/home/no47agyrt0nt/onlinejob2`
-   **Mobile API path** (if using router): `/home/no47agyrt0nt/lv_mobile_app_api`

## Current Setup Recommendation

Based on your setup, I recommend:

1. **Root index.php** → Points to `onlinejob2/` (main app)
2. **mobile-api/index.php** → Points to `lv_mobile_app_api/` (mobile API)

This keeps them separate and easier to manage.

## If onlinejob2 Path is Wrong

If your main Laravel app is in a **different location**, update the `$laravelRoot` variable in the root `index.php`:

```php
// Change this to match your actual main app location
$laravelRoot = '/home/no47agyrt0nt/YOUR_ACTUAL_MAIN_APP_DIRECTORY';
```

## Quick Diagnostic

Create a test file `/home/no47agyrt0nt/public_html/check-main-app.php`:

```php
<?php
$paths = [
    '/home/no47agyrt0nt/onlinejob2',
    '/home/no47agyrt0nt/onlinejob',
    '/home/no47agyrt0nt/public_html/../onlinejob2',
];

foreach ($paths as $path) {
    $autoload = $path . '/vendor/autoload.php';
    echo "Checking: {$autoload}<br>";
    if (file_exists($autoload)) {
        echo "✅ FOUND! Main app is at: {$path}<br>";
    } else {
        echo "❌ Not found<br>";
    }
    echo "<br>";
}
```

Visit: `https://onlinejobs.my/check-main-app.php`

This will tell you where your main app actually is.

**Delete this file after use!**

