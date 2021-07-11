<?php

namespace Modules\Item\Requests;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;
use Modules\Item\Requests\Rules\CorrectPrice;

class UpdateItemRequest extends ApiFormRequest
{
    /**
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
}
