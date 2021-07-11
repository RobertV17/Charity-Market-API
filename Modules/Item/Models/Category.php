<?php

namespace Modules\Item\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Item\Database\Factories\CategoryFactory;

/**
 * Class Category
 * @package Modules\Item\Models
 */
class Category extends Model
{
    use HasFactory;

    /**
     * @return CategoryFactory
     */
    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    /**
     * @var string
     */
    protected $table = 'category';
}
