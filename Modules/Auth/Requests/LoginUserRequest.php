<?php

namespace Modules\Auth\Requests;

use App\Http\Requests\ApiFormRequest;

class LoginUserRequest extends ApiFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|string|max:255',
            'password' => 'required|string||max:150'
        ];
    }
}
