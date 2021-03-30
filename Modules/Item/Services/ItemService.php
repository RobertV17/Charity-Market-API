<?php


namespace Modules\Item\Services;


use Modules\Item\Repositories\ItemRepository;
use \Illuminate\Database\Eloquent\Collection;

class ItemService
{
    /**
     * @var ItemRepository
     */
    protected $repository;

    public function __construct(
        ItemRepository $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     * @return Collection
     */
    public function getAll():Collection
    {
        return $this->repository->getAll();
    }


}
