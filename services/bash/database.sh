docker-compose exec app php artisan migrate;
docker-compose exec app php artisan db:seed --class="Modules\Item\Database\Seeders\CategorySeeder";
docker-compose exec app php artisan db:seed --class="Modules\User\Database\Seeders\UserSeeder";
docker-compose exec app php artisan db:seed --class="Modules\Item\Database\Seeders\ItemSeeder";
