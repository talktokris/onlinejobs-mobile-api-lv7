#!/bin/bash

# Docker Start Script for Laravel 7
# This script helps you quickly set up and start the Docker environment

set -e

echo "🐳 Laravel 7 Docker Setup"
echo "========================"
echo ""

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker Desktop first."
    exit 1
fi

echo "✅ Docker is running"
echo ""

# Build and start containers
echo "📦 Building and starting containers..."
docker compose up -d --build

echo ""
echo "⏳ Waiting for MySQL to be ready..."
sleep 10

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    docker compose exec -T app cp .env.example .env 2>/dev/null || echo ".env.example not found, creating basic .env"
    docker compose exec -T app php artisan key:generate
    echo ""
    echo "⚠️  Please update .env file with your database settings:"
    echo "   DB_HOST=mysql"
    echo "   DB_DATABASE=onlinejobs_db"
    echo "   DB_USERNAME=onlinejobs_user"
    echo "   DB_PASSWORD=root"
    echo ""
fi

# Install dependencies if vendor folder doesn't exist
if [ ! -d "vendor" ]; then
    echo "📥 Installing Composer dependencies..."
    docker compose exec -T app composer install --no-dev --optimize-autoloader
    echo ""
fi

# Set permissions
echo "🔐 Setting file permissions..."
docker compose exec -T app chmod -R 775 storage bootstrap/cache
docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache
echo ""

# Start Laravel development server
echo "🚀 Starting Laravel development server..."
docker compose exec -d app php artisan serve --host=0.0.0.0 --port=8000

echo ""
echo "✅ Setup complete!"
echo ""
echo "🌐 Access your application:"
echo "   Laravel App: http://localhost:8000"
echo "   phpMyAdmin:  http://localhost:8080"
echo ""
echo "📝 Useful commands:"
echo "   View logs:    docker compose logs -f app"
echo "   Stop:         docker compose stop"
echo "   Shell access: docker compose exec app bash"
echo ""

