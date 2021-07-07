<?php

namespace Tests\Feature\Api\Item;

use Illuminate\Support\Facades\File;
use Modules\Item\Models\Category;
use Modules\Item\Models\Item;
use Modules\User\Models\User;
use Tests\Feature\Api\ApiTestCase;

/**
 * Class ItemTestCase
 * @package Tests\Feature\Api\Item
 */
class ItemTestCase extends ApiTestCase
{
    private $itemsPreviewImagesPath = 'images/items/preview';

    protected function removeStoredItemPreviewImages(): void
    {
        File::deleteDirectory(public_path($this->itemsPreviewImagesPath));
    }

    /**
     * @param  int  $count
     */
    protected function createItems(int $count = 25): void
    {
        User::factory()->count(2)->create();
        Category::factory()->count(3)->create();
        Item::factory()->count($count)->create();
    }
}
