<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Item\Services\ItemService;

class ItemController extends Controller
{
    /**
     * @var ItemService
     */
    protected $service;

    public function __construct(
        ItemService $service
    )
    {
        $this->service = $service;
    }


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
    public function getAllItems(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'items' => $this->service->getAll()
        ]);
    }
}
