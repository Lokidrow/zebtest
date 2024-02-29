<?php

namespace App\Http\Controllers;

use App\Http\Requests\userAuthRequest;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{

    /**
     * Авторизация (вход или регистрация) пользователяs
     * @param userAuthRequest $request
     * @return JsonResponse
     */
    public function userAuth(userAuthRequest $request): JsonResponse
    {
        $response = users()->authUser($request->all());

        return response()->json($response);
    }

}
