<?php

namespace Modules\Item\Services;

use Illuminate\Http\UploadedFile;
use Modules\Item\Factories\ItemFactory;
use Modules\Item\Repositories\ItemRepository;
use Modules\Item\Models\Item;
use Modules\Item\Dto\SaveItemDto;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use \Exception;
use Modules\User\Models\User;

class ItemService
{
    /**
     * @var ItemRepository
     */
    protected $repository;

    /**
     * @var ItemFactory
     */
    protected $factory;

    public function __construct(
        ItemRepository $repository,
        ItemFactory $factory
    )
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    /**
     * @param $user
     * @param $dto
     * @return Item
     */
    public function addItemByUser($user, $dto): Item
    {
        $item = $this->factory->createByUser($user);

        $this->populate($item, $dto);
        $this->save($item);

        return $item;
    }

    /**
     * @param Item $item
     * @param SaveItemDto $dto
     */
    public function populate(Item $item, SaveItemDto $dto): void
    {
        $item->fill($dto->toArray());
        $this->setPreviewPhoto($item, $dto->photo);
    }

    /**
     * @param Item $item
     * @param UploadedFile $photo
     */
    public function setPreviewPhoto(Item $item, UploadedFile $photo): void
    {
        $fileName = time() . '.' . $photo->extension();
        $photo->move(public_path('images/items/preview'), $fileName);

        $item->photo_url = url('images/items/preview/' . $fileName);
    }

    /**
     * @param Item $item
     */
    public function save(Item $item): void
    {
        $this->factory->save($item);
    }

    /**
     * @param int $id
     * @return Item
     * @throws Exception
     */
    public function getTryById(int $id): Item
    {
        $item = $this->repository->getById($id);

        if (!$item) {
            throw new Exception('Item not founded');
        }

        return $item;
    }

    /**
     * @param Item $item
     * @param User $user
     * @throws Exception
     */
    public function drop(Item $item, User $user): void
    {
        $this->checkUserAccessToItem($user, $item);
        $this->repository->drop($item);
    }

    /**
     * @param User $user
     * @param Item $item
     * @throws Exception
     */
    public function checkUserAccessToItem(User $user, Item $item): void
    {
        if ($user->id !== $item->user_id) {
            throw new Exception('Access denied');
        }
    }

    /**
     * @param Item $item
     * @param SaveItemDto $dto
     * @param User $user
     * @throws Exception
     */
    public function update(Item $item, SaveItemDto $dto, User $user): void
    {
        $this->checkUserAccessToItem($user, $item);

        $item->fill(array_filter($dto->toArray()));

        if($dto->photo) {
            $this->setPreviewPhoto($item, $dto->photo);
        }

        $this->save($item);
    }
}
