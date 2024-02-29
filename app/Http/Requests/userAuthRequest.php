<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class userAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'access_token' => '',
            'id' => 'integer',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'country' => 'required|max:255',
            'city' => 'required|max:255',
            'sig' => 'required',
        ];
    }

    /**
     * После всех валидаций - проверяем подпись
     * @return array
     */
    public function after(): array
    {
        return [function(\Illuminate\Validation\Validator $validator)
        {
            if ($this->checkSig()) {
                $validator->errors()
                    ->add('sig', 'Ошибка авторизации в приложении');
            }
        }];
    }

    /**
     * Проверка подписи
     * @return bool
     */
    public function checkSig(): bool
    {
        $all = $this->all();

        // Поле sig не участвует в формировании строки для проверки
        $sig = $all['sig'];
        unset($all['sig']);

        ksort($all);

        // Сформируйте строку $str из полученных параметров
        $str = implode('', array_map(function($k, $v) {
            return $k . '=' . $v;
        }, array_keys($all), array_values($all))) . env('SECRET_KEY');

        $str = mb_strtolower(md5($str), 'UTF-8');

        return $sig !== $str;
    }

    /**
     * Формирование ответа в виде json если запрос не прошёл валидациюs
     * @param Validator $validator
     * @return void
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        $respArr = [
            'error' => 'error',
            'error_key' => 'error',
        ];
        if (!empty($validator->errors()->get('sig'))) {
            $respArr = [
                'error' => $validator->errors()->get('sig')[0],
                'error_key' => 'signature error',
            ];
        } else {
            foreach ($validator->errors()->messages() as $key => $value) {
                $respArr = [
                    'error' => $value,
                    'error_key' => $key,
                ];
            }
        }
        throw new ValidationException($validator, new Response($respArr, 200));
    }

}
