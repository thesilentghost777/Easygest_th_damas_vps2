#!/bin/bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "All Optimize for a proper work ! God is under control"
