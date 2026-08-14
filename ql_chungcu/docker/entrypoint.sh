#!/bin/bash
set -e

cd /var/www/html

# Flag file để check đã chạy setup chưa
SETUP_FLAG="/var/www/html/storage/.setup_done"

echo "Starting Laravel application..."

# Verify artisan exists
if [ ! -f "artisan" ]; then
    echo "ERROR: artisan file not found!"
    exit 1
fi

# Chỉ chạy setup 1 lần duy nhất
if [ ! -f "$SETUP_FLAG" ]; then
    echo "First run - running setup..."

    # Create storage symlink
    php artisan storage:link 2>/dev/null || true

    # Wait for database
    echo "Waiting for database..."
    for i in {1..10}; do
        if php artisan db:show 2>/dev/null; then
            echo "✓ Database connected"
            break
        fi
        sleep 3
    done

    # Run migrations
    echo "Running migrations..."
    php artisan migrate --force || echo "Migration failed"

    # Cache config
    php artisan config:cache
    php artisan route:cache

    # Đánh dấu đã setup xong
    touch "$SETUP_FLAG"
    echo "✓ Setup completed"
else
    echo "✓ Already initialized, skipping setup"
fi

echo "✓ Application ready"

# Start services
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
