<?php

namespace Modules\Item\Repositories;

use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Item\Models\Item;

class ItemRepository
{
    /**
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Item::query()->paginate(10);
    }
}
