<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Item\Dto\SaveItemDto;
use Modules\Item\Requests\StoreItemRequest;
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
     *     operationId="itemsAll",
     *     summary="Получение всех существующих товаров с пагинацией",
     *     tags={"Items"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Номер страницы",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Все хорошо",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ошибка на стороне сервера"
     *     )
     * )
     */
    public function get(): JsonResponse
    {
        return response()->success(null, $this->service->getAll());
    }

    /**
     * @OA\Post(
     *     path="/items/add",
     *     operationId="itemsAdd",
     *     summary="Добавление товара авторизированным пользователем",
     *     tags={"Items"},
     *    	@OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="title",
     *                      description="Наименование",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="cat_id",
     *                      description="Id категории",
     *                      type="integer",
     *                   ),
     *                  @OA\Property(
     *                      property="desc",
     *                      description="Описание",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="price",
     *                      description="Цена",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="photo",
     *                      description="Фотография",
     *                      type="file",
     *                      @OA\Items(type="string", format="binary")
     *                   ),
     *               ),
     *           ),
     *       ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Товар добавлен",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Ошибка на стороне сервера"
     *     )
     * )
     */
    public function add(StoreItemRequest $request)
    {
        $dto = SaveItemDto::populateByArray($request->toArray());
        // @todo-robert получать ткущего пользователя, когда будет авторизация
        $user = 1;
        $item = $this->service->addItemByUser($user, $dto);

        return response()->success('Item was added',
            [
                'item' => $item->toArray()
            ]);
    }
}
