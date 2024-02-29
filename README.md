Установка проекта:

Переходим в папк проекта.

chmod 0777 storage
<br>
chmod 0777 storage/app
<br>
chmod 0777 storage/app/public
<br>
chmod 0777 storage/logs
<br>
chmod 0777 storage/framework
<br>
chmod 0777 storage/framework/cache
<br>
chmod 0777 storage/framework/cache/data
<br>
chmod 0777 storage/framework/sessions
<br>
chmod 0777 storage/framework/testing
<br>

cp .env.example .env
<br>
Настройте доступ к БД в этом файле

php artisan key:generate
<br>
composer install
<br>
php artisan migrate
<br>

Настройте веб сервер на папку public проекта.

Теперь можно выполнять запросы.

На выполнение задания было потрачено 4ч.
