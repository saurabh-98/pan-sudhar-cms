<?php
namespace App\Services;

use App\Repositories\NavigationMenuRepository;
use App\DTO\NavigationMenuDTO;

class NavigationMenuService
{
    protected $navigationRepository;

    public function __construct(NavigationMenuRepository $navigationRepository)
    {
        $this->navigationRepository = $navigationRepository;
    }

    public function getAllMenus()
    {
        return $this->navigationRepository->getAll();
    }

     public function list()
    {
        return $this->navigationRepository->getAll();
    }

    public function getActiveMenus()
    {
        return $this->navigationRepository->getAll()
            ->where('status', 1)
            ->sortBy('order');
    }

    public function createMenu(NavigationMenuDTO $dto)
    {
        return $this->navigationRepository->create($dto->toArray());
    }

    public function updateMenu($id, NavigationMenuDTO $dto)
    {
        return $this->navigationRepository->update($id, $dto->toArray());
    }

    public function deleteMenu($id)
    {
        return $this->navigationRepository->delete($id);
    }

    public function findMenu($id)
    {
        return $this->navigationRepository->find($id);
    }
}