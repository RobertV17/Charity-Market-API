# Charity Market API
> API для взаимодействия с клиентской частью интернет-магазина Charity Market.


В разработке API применяется PHP фреймворк Laravel 8, а для удобства использования все сервисы обернуты в многоконтейнерное окружение (Docker + Docker Compose)

## Установка

OS X & Linux:

```sh
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed --class="Modules\Item\Database\Seeders\CategorySeeder"
docker-compose exec app php artisan db:seed --class="Modules\User\Database\Seeders\UserSeeder"
docker-compose exec app php artisan db:seed --class="Modules\Item\Database\Seeders\ItemSeeder"
```

Документация к API тут - http://localhost/api/documentation  
Для ее обновления используйте:
```sh
docker-compose exec app php artisan l5-swagger:generate
```
