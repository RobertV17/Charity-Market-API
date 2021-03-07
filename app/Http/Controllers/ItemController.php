<?php

namespace App\Http\Controllers;

use App\Models\Item;

class ItemController extends Controller
{
    /**
     * @OA\Get(
     *     path="/items/all",
     *     summary="Получение всех существующих товаров.",
     *     tags={"Items"},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Item")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description=""
     *     )
     * )
     */
    public function getAllItems() {
        return response()->json([
            'status' => 'success',
            'items' => Item::all()
        ]);
    }
}
