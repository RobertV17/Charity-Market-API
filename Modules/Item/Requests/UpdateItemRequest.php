<?php

namespace Modules\Item\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'  => 'nullable|string|max:100|unique:item',
            'desc'   => 'nullable|string|max:255',
            'price'  => 'nullable',
            'photo'  => 'nullable|image|max:10000',
            'cat_id' => 'nullable|exists:category,id'
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->fail(
                $validator->errors(),
                'Incorrect data'
            )
        );
    }
}
