<?php

namespace Modules\Item\Services;

use Illuminate\Http\UploadedFile;
use Modules\Item\Factories\ItemFactory;
use Modules\Item\Repositories\ItemRepository;
use Modules\Item\Models\Item;
use Modules\Item\Dto\SaveItemDto;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     */
    public function save(Item $item): void
    {
        $this->factory->save($item);
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
}
