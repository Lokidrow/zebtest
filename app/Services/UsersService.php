<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserSession;
use Illuminate\Support\Facades\DB;

class UsersService {

    /**
     * API авторизации пользователя
     * @param array $params
     * @return array
     */
    public function authUser(array $params): array
    {
        $user = User::find($params['id']);
        if (empty($user)) {
            $user = $this->registerUser($params);
        } else {
            $user = $this->loginUser($params, $user);
        }
        $this->storeSession($user, $params['access_token']);

        return $this->getUserData(User::find($params['id']));
    }

    /**
     * Регистрация нового пользователя
     * @param array $params
     * @return User
     */
    private function registerUser(array $params): User
    {
        $user = User::create([
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'city' => $params['city'],
            'country' => $params['country'],
        ]);
        $user->id = $params['id'];
        $user->save();
        return $user;
    }

    /**
     * Авторизация существующего пользователя
     * @param array $params
     * @param User $user
     * @return User
     */
    private function loginUser(array $params, User $user): User
    {
        return $this->updateUser($user, $params);
    }

    /**
     * Обновление данных пользователя
     * @param User $user
     * @param array $params
     * @return User
     */
    private function updateUser(User $user, array $params): User
    {
        $user->id = $params['id'];
        $user->first_name = $params['first_name'];
        $user->last_name = $params['last_name'];
        $user->city = $params['city'];
        $user->country = $params['country'];
        $user->save();

        return $user;

    }

    /**
     * Сохранении сессии пользователя
     * @param User $user
     * @param string $access_token
     * @return void
     */
    private function storeSession(User $user, string $access_token): void
    {
        if ($user->user_session) {
            $session = $user->user_session;
        } else {
            $session = new UserSession();
            $session->user_id = $user->id;
        }
        $session->access_token = $access_token;
        $session->save();
    }

    /**
     * Формируем ответ API по данным пользователя
     * @param User $user
     * @return array
     */
    private function getUserData(User $user): array
    {
        return [
            'access_token' => $user->user_session->access_token,
            'user_info' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'city' => $user->city,
                'country' => $user->country,
            ],
            'error' => '',
            'error_key' => '',
        ];
    }

}
