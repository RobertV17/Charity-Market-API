<?php

namespace Modules\Item\Requests;

use App\Http\Requests\ApiFormRequest;
use Modules\Item\Requests\Rules\CorrectPrice;

class StoreItemRequest extends ApiFormRequest
{
    /**
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
}
