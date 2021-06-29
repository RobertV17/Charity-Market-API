<?php

namespace Modules\Item\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Item\Database\Factories\ItemFactory;

/**
 * Class Item
 * @package Modules\Item\Models
 */
class Item extends Model
{
    use HasFactory;

    /**
     * @return ItemFactory
     */
    protected static function newFactory(): ItemFactory
    {
        return ItemFactory::new();
    }

    /**
     * @var string
     */
    protected $table = 'item';

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'desc',
        'price',
        'photo_url',
        'cat_id',
        'user_id'
    ];

    /**
     * @param $value
     *
     * @return float
     */
    public function getPriceAttribute($value): float
    {
        return (float)$value;
    }
}
