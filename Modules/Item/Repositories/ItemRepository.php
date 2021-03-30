<?php


namespace Modules\Item\Repositories;


use \Illuminate\Database\Eloquent\Collection;
use Modules\Item\Models\Item;

class ItemRepository
{
    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Item::all();
    }
}
