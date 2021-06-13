<?php


namespace Modules\Item\Factories;


use Modules\Item\Models\Item;
use Modules\User\Models\User;

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
     * @param User $user
     * @return Item
     */
    public function createByUser(User $user): Item
    {
        $item = new Item;
        $item->user_id = $user->id;

        return $item;
    }
}
