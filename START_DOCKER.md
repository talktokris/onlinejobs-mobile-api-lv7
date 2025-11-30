# 🚀 Quick Start - Docker Setup

## ⚠️ Important: Use `docker compose` (space) not `docker-compose` (hyphen)

Docker Compose v2 uses `docker compose` with a **space**, not `docker-compose` with a hyphen.

## ✅ Quick Start (Choose One Method)

### Method 1: Automated Script (Easiest)

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7/laravel-7
./docker-start.sh
```

### Method 2: Manual Commands

```bash
# 1. Navigate to laravel-7 directory
cd /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7/laravel-7

# 2. Start containers
docker compose up -d --build

# 3. Install dependencies
docker compose exec app composer install --no-dev --optimize-autoloader

# 4. Setup .env
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate

# 5. Edit .env file - Update database settings:
#    DB_HOST=mysql
#    DB_DATABASE=onlinejobs_db
#    DB_USERNAME=onlinejobs_user
#    DB_PASSWORD=root

# 6. Set permissions
docker compose exec app chmod -R 775 storage bootstrap/cache

# 7. Start Laravel server
docker compose exec app php artisan serve --host=0.0.0.0 --port=8000
```

## 🌐 Access Your App

- **Laravel API**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080 (root/root)

## 📝 Common Commands

```bash
# View logs
docker compose logs -f app

# Stop containers
docker compose stop

# Start containers
docker compose start

# Access container shell
docker compose exec app bash

# Run artisan commands
docker compose exec app php artisan migrate
docker compose exec app php artisan route:list
```

## 🔧 Troubleshooting

**"command not found: docker-compose"**
- ✅ Use `docker compose` (space) instead of `docker-compose` (hyphen)

**"no configuration file provided"**
- ✅ Make sure you're in the `laravel-7` directory
- ✅ Check that `docker-compose.yml` exists: `ls docker-compose.yml`

**Docker not running**
- ✅ Start Docker Desktop application first

**Port already in use**
- ✅ Change port in `docker-compose.yml`: `"8001:8000"`

