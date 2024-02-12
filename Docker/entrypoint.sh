composer install

chmod -R 777 storage bootstrap/cache

php artisan migrate
php artisan key:generate
php artisan migrate:fresh --seed
