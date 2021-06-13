<?php

namespace Modules\Item\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        // @todo-robert для price обдумать валидацию
        return [
            'title'  => 'required|string|max:100|unique:item',
            'desc'   => 'required|string|max:255',
            'price'  => 'required',
            'photo'  => 'required|image|max:10000',
            'cat_id' => 'required|exists:category,id'
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->error(
                [],
                $validator->errors()
            )
        );
    }
}
