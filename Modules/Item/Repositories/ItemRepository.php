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

    /**
     * @param int $id
     * @return Item|null
     */
    public function getById(int $id): ?Item
    {
        return Item::query()->where('id', $id)->first();
    }

    /**
     * @param Item $item
     * @throws \Exception
     */
    public function drop(Item $item)
    {
        $item->delete();
    }
}
