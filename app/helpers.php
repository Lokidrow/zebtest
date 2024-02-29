<?php

use App\Services\UsersService;

/**
 * Получение экземпляра сервиса пользователей
 * @return UsersService
 */
function users(): UsersService
{
    return new UsersService();
}

/**
 * Отладочная функция
 * @param $s
 * @return void
 */
function pr($s) {
    echo '<pre>';
    print_r($s);
    echo '</pre>';
}
