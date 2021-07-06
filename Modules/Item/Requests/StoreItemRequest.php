<?php

namespace Modules\Item\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Item\Requests\Rules\CorrectPrice;

class StoreItemRequest extends FormRequest
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
            'title'  => 'required|string|max:100|unique:item',
            'desc'   => 'required|string|max:255',
            'price'  => ['required', new CorrectPrice],
            'cat_id' => 'required|integer|exists:category,id',
            'photo'  => 'required|image|max:10000'
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
