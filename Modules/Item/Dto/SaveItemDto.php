<?php


namespace Modules\Item\Dto;

use App\Dto\Dto;

class SaveItemDto extends Dto
{
    public $title;
    public $desc;
    public $price;
    public $cat_id;
    public $photo;
}
