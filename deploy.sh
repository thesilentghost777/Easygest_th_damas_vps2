composer install --no-dev --optimize-autoloader
php artisan migrate
php artisan cache:clear
php artisan config:cache
sudo chown -R www-data:www-data /var/www/easygest
