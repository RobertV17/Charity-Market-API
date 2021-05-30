<?php

namespace Modules\Item\Models;


use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item';
    protected $fillable = [
        'title',
        'desc',
        'price',
        'photo_url',
        'cat_id',
        'user_id'
    ];
}
