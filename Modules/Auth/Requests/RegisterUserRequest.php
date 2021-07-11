<?php

namespace Modules\Auth\Requests;

use App\Http\Requests\ApiFormRequest;

class RegisterUserRequest extends ApiFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'login'    => 'required|string|max:150|unique:users',
            'email'    => 'required|string|max:255|unique:users',
            'password' => 'required|string||max:150'
        ];
    }
}
