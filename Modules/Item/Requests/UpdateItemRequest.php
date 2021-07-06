<?php

namespace Modules\Item\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Item\Requests\Rules\CorrectPrice;

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
        $itemId = $this->route('id');

        return [
            'title'  => ['nullable','string', 'max:100', Rule::unique('item')->ignore($itemId)],
            'desc'   => 'nullable|string|max:255',
            'price'  => ['nullable', new CorrectPrice],
            'photo'  => 'nullable|image|max:10000',
            'cat_id' => 'nullable|integer|exists:category,id'
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
