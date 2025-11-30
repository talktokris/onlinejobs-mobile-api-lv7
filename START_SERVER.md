# 🚀 How to Start Laravel Server

The Docker container is running, but you need to start the Laravel development server manually.

## Quick Start

Run this command in your terminal:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/projects/seelaan/onlinejobs/backend-v7/laravel-7
docker compose exec app bash -c "cd /var/www/html && php artisan serve --host=0.0.0.0 --port=8000"
```

This will start the server and keep it running. Press `Ctrl+C` to stop it.

## Start in Background

To start the server in the background:

```bash
docker compose exec -d app bash -c "cd /var/www/html && php artisan serve --host=0.0.0.0 --port=8000"
```

## Access Your Application

Once the server is running:
- **Laravel API**: http://localhost:8000
- **API Routes**: http://localhost:8000/api/countries

## Check Server Status

```bash
# Check if server is running
docker compose exec app ps aux | grep "artisan serve"

# View logs
docker compose logs -f app
```

## Troubleshooting

If you get errors, try:

```bash
# Clear caches
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear

# Check Laravel version
docker compose exec app php artisan --version

# Test database connection
docker compose exec app php artisan tinker
# Then type: DB::connection()->getPdo();
```

