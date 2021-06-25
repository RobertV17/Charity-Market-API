<?php

namespace Modules\Item\Repositories;

use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Item\Models\Item;

/**
 * Class ItemRepository
 * @package Modules\Item\Repositories
 */
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
     * @param $id
     *
     * @return Item|null
     */
    public function getById($id): ?Item
    {
        //todo-robert проверит на предмет уязвимости SQL injection
        return Item::query()->where('id', $id)->first();
    }


    /**
     * @param  Item  $item
     *
     * @throws \Exception
     */
    public function drop(Item $item)
    {
        $item->delete();
    }
}
