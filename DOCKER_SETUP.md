# Docker Setup for Laravel 7

This Docker setup allows you to test your Laravel 7 application locally using PHP 7.4, even if your local machine has PHP 8.x.

## 📋 Prerequisites

- Docker Desktop installed and running
- Docker Compose (usually included with Docker Desktop)

## 🚀 Quick Start

### Step 1: Build and Start Containers

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7/laravel-7
docker compose up -d --build
```

This will:
- Build PHP 7.4 container with all required extensions
- Start MySQL 5.7 database
- Start phpMyAdmin (optional, for database management)

### Step 2: Install Dependencies

```bash
# Enter the app container
docker compose exec app bash

# Inside the container, install Composer dependencies
composer install --no-dev --optimize-autoloader

# Exit container
exit
```

Or run directly:
```bash
docker compose exec app composer install --no-dev --optimize-autoloader
```

### Step 3: Configure Environment

```bash
# Copy .env.example to .env (if not exists)
docker compose exec app cp .env.example .env

# Generate application key
docker compose exec app php artisan key:generate

# Edit .env file with your database settings
# DB_HOST=mysql
# DB_DATABASE=onlinejobs_db
# DB_USERNAME=onlinejobs_user
# DB_PASSWORD=root
```

### Step 4: Set Permissions

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Step 5: Run Migrations (Optional)

```bash
docker compose exec app php artisan migrate
```

### Step 6: Start Development Server

```bash
docker compose exec app php artisan serve --host=0.0.0.0 --port=8000
```

Or run in background:
```bash
docker compose exec -d app php artisan serve --host=0.0.0.0 --port=8000
```

## 🌐 Access Your Application

- **Laravel App**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
  - Server: `mysql`
  - Username: `root`
  - Password: `root`

## 📝 Useful Commands

### View Logs
```bash
docker compose logs -f app
```

### Stop Containers
```bash
docker compose stop
```

### Start Containers
```bash
docker compose start
```

### Stop and Remove Containers
```bash
docker compose down
```

### Stop and Remove Containers + Volumes (⚠️ deletes database)
```bash
docker compose down -v
```

### Execute Commands in Container
```bash
# Run artisan commands
docker compose exec app php artisan [command]

# Run composer commands
docker compose exec app composer [command]

# Access container shell
docker compose exec app bash
```

### Rebuild Containers (after Dockerfile changes)
```bash
docker compose up -d --build
```

## 🔧 Database Configuration

The `.env` file should have these database settings:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=onlinejobs_db
DB_USERNAME=onlinejobs_user
DB_PASSWORD=root
```

**Note**: `DB_HOST=mysql` (not `localhost` or `127.0.0.1`) because we're using Docker service name.

## 🐛 Troubleshooting

### Port Already in Use

If port 8000 is already in use, change it in `docker compose.yml`:
```yaml
ports:
  - "8001:8000"  # Change 8001 to any available port
```

### Permission Denied Errors

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Container Won't Start

Check logs:
```bash
docker compose logs app
```

### Composer Install Fails

Make sure you're running it inside the container:
```bash
docker compose exec app composer install --ignore-platform-reqs
```

### Database Connection Failed

1. Make sure MySQL container is running: `docker compose ps`
2. Check `.env` has `DB_HOST=mysql` (not `localhost`)
3. Wait a few seconds after starting containers for MySQL to initialize

## 📦 What's Included

- **PHP 7.4** with FPM
- **MySQL 5.7** database
- **phpMyAdmin** for database management
- **Composer** pre-installed
- **Required PHP Extensions**: pdo_mysql, mbstring, exif, pcntl, bcmath, gd, zip

## 🎯 Next Steps

1. Test your API endpoints: http://localhost:8000/api/countries
2. Test authentication endpoints
3. Verify all routes are working
4. Once everything works, deploy to production server

## 💡 Tips

- Your code changes are automatically reflected (volumes are mounted)
- You can use your local IDE/editor - files are synced
- Database persists in Docker volume `mysql_data`
- To reset database: `docker compose down -v` (⚠️ deletes all data)

