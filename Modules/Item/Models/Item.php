<?php

namespace Modules\Item\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *  schema="Item",
 *  @OA\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @OA\Property(
 *      property="title",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="decs",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="price",
 *      type="decimal"
 *  ),
 *  @OA\Property(
 *      property="cat_id",
 *      type="integer"
 *  ),
 *  @OA\Property(
 *      property="user_id",
 *      type="integer"
 *  )
 * )
 */
class Item extends Model
{
    protected $table = 'item';
}