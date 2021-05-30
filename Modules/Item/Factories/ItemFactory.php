<?php


namespace Modules\Item\Factories;


use Modules\Item\Models\Item;

class ItemFactory
{
    /**
     * @param Item $item
     */
    public function save(Item $item): void
    {
        $item->save();
    }

    /**
     * @return Item
     */
    public function create(): Item
    {
        return new Item;
    }
}
