<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Item\Dto\SaveItemDto;
use Modules\Item\Requests\StoreItemRequest;
use Modules\Item\Requests\UpdateItemRequest;
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
     *     security={{"bearerAuth":{}}},
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
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Пользователь не авторизован",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function all(): JsonResponse
    {
        return response()->success(null, $this->service->getAll());
    }

    /**
     * @OA\Get(
     *     path="/items/{id}",
     *     operationId="item",
     *     security={{"bearerAuth":{}}},
     *     summary="Получение указанного товара",
     *     tags={"Items"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id товара",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Все хорошо",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Пользователь не авторизован",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Указанный товар не найден",
     *         @OA\JsonContent()
     *     )
     * )
     */

    /**
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function show($id): JsonResponse
    {
        $item = $this->service->getTryById($id);

        return response()->success(null,
            [
                'item' => $item
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/items/add",
     *     operationId="itemsAdd",
     *     security={{"bearerAuth":{}}},
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
     *                      description="Цена (в формате 145.00)",
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
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Пользователь не авторизован",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Указанные данные некорректны",
     *         @OA\JsonContent()
     *     )
     * )
     */

    /**
     * @param StoreItemRequest $request
     * @return JsonResponse
     */
    public function add(StoreItemRequest $request): JsonResponse
    {
        $dto = SaveItemDto::populateByArray($request->toArray());
        $user = Auth::user();

        $item = $this->service->addItemByUser($user, $dto);

        return response()->success('Item was added',
            [
                'item' => $item->toArray()
            ]);
    }

    /**
     * @OA\Post(
     *     path="/items/update/{id}",
     *     operationId="itemsUpdate",
     *     security={{"bearerAuth":{}}},
     *     summary="Изменение товара (доступ только у создателя)",
     *     tags={"Items"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id товара",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *    	@OA\RequestBody(
     *          description="Указывать нужно только те поля что были изменены,
     *                       а те что не тронуты не отсылай или пиши NULL",
     *          required=false,
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
     *         description="Товар изменен",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Пользователь не авторизован",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Указанные данные некорректны или пользователь не имеет права изменять этот товар,
     *                      так как не является его продавцом",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Указанный товар не найден",
     *         @OA\JsonContent()
     *     )
     * )
     */

    /**
     * @param $id
     * @param UpdateItemRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function update($id, UpdateItemRequest $request): JsonResponse
    {
        $item = $this->service->getTryById($id);
        $dto = SaveItemDto::populateByArray($request->toArray());
        $user = Auth::user();

        $this->service->update($item, $dto, $user);

        return response()->success('Item was updated', [
            'item' => $item
        ]);
    }

    /**
     * @OA\Delete (
     *     path="/items/drop/{id}",
     *     operationId="itemsDrop",
     *     security={{"bearerAuth":{}}},
     *     summary="Удаление товара (доступ только у создателя)",
     *     tags={"Items"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id товара",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Все хорошо",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Пользователь не авторизован",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Пользователь не имеет права изменять этот товар, так как не является его продавцом",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Указанный товар не найден",
     *         @OA\JsonContent()
     *     )
     * )
     */

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function drop($id): JsonResponse
    {
        $item = $this->service->getTryById($id);
        $user = Auth::user();
        $this->service->drop($item, $user);

        return response()->success('Item was deleted', null);
    }
}
