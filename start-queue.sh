#!/bin/bash

# Script pour démarrer le queue worker Laravel
cd /var/www/caverne-enfants

# Kill existing queue workers
pkill -f "artisan queue:work"

# Start new queue worker in background
nohup php artisan queue:work --tries=3 --timeout=90 > /var/log/laravel-queue.log 2>&1 &

echo "Queue worker started"