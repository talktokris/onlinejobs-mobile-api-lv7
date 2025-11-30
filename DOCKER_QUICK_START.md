# 🐳 Docker Quick Start Guide

## One-Command Setup

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7/laravel-7
./docker-start.sh
```

That's it! The script will:
1. ✅ Build and start Docker containers
2. ✅ Install Composer dependencies
3. ✅ Set up `.env` file
4. ✅ Set file permissions
5. ✅ Start Laravel development server

## Manual Setup (Alternative)

If you prefer to do it step by step:

```bash
# 1. Start containers
docker compose up -d --build

# 2. Install dependencies
docker compose exec app composer install --no-dev --optimize-autoloader

# 3. Setup .env
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate

# 4. Update .env database settings:
# DB_HOST=mysql
# DB_DATABASE=onlinejobs_db
# DB_USERNAME=onlinejobs_user
# DB_PASSWORD=root

# 5. Set permissions
docker compose exec app chmod -R 775 storage bootstrap/cache

# 6. Start server
docker compose exec app php artisan serve --host=0.0.0.0 --port=8000
```

## 🌐 Access

- **API**: http://localhost:8000/api/countries
- **phpMyAdmin**: http://localhost:8080 (root/root)

## 📝 Common Commands

```bash
# View logs
docker compose logs -f app

# Stop containers
docker compose stop

# Start containers
docker compose start

# Restart containers
docker compose restart

# Access container shell
docker compose exec app bash

# Run artisan commands
docker compose exec app php artisan migrate
docker compose exec app php artisan route:list

# Run composer commands
docker compose exec app composer update
```

## 🔧 Troubleshooting

**Port 8000 already in use?**
- Change port in `docker-compose.yml`: `"8001:8000"`

**Note:** Docker Compose v2 uses `docker compose` (space) instead of `docker-compose` (hyphen)

**Permission errors?**
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

**Database connection failed?**
- Make sure `.env` has `DB_HOST=mysql` (not `localhost`)
- Wait 10-15 seconds after starting containers for MySQL to initialize

**Container won't start?**
```bash
docker-compose logs app
```

## 🎯 What's Running

- **PHP 7.4** - Laravel 7 compatible
- **MySQL 5.7** - Database server
- **phpMyAdmin** - Database management UI

All containers are on the same Docker network, so they can communicate using service names (`mysql`, `app`).

