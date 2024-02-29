Установка проекта:

Переходим в папк проекта.

chmod 0777 storage
chmod 0777 storage/app
chmod 0777 storage/app/public
chmod 0777 storage/logs
chmod 0777 storage/framework
chmod 0777 storage/framework/cache
chmod 0777 storage/framework/cache/data
chmod 0777 storage/framework/sessions
chmod 0777 storage/framework/testing

cp .env.example .env
Настройте доступ к БД в этом файле

php artisan key:generate
composer install
php artisan migrate

Настройте веб сервер на папку public проекта.

Теперь можно выполнять запросы.

На выполнение задания было потрачено 4ч.
