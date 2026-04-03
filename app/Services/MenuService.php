<?php

namespace App\Services;

use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\Cache;

class MenuService {

    protected $menuRepository;

    public function __construct(MenuRepository $menuRepository) // ✅ DI FIX
    {
        $this->menuRepository = $menuRepository;
    }

    // ✅ GET ALL
    public function getAll() {
        return $this->menuRepository->all();
    }

    // ✅ STORE
    public function store($dto)
    {
        $data = $this->menuRepository->create([
            'name' => $dto->name,
            'price' => $dto->price,
            'category_id' => $dto->category_id,
            'image' => $dto->image,

            // ✅ NEW FIELDS
            'description' => $dto->description,
            'specifications' => $dto->specifications,
        ]);

        $this->clearCache();

        return $data;
    }

    // ✅ UPDATE
    public function update($id, $dto)
    {
        $data = [
            'name' => $dto->name,
            'price' => $dto->price,
            'category_id' => $dto->category_id,
            'description' => $dto->description,
            'specifications' => $dto->specifications,
        ];

        if ($dto->image) {
            $data['image'] = $dto->image;
        }

        $data = $this->menuRepository->update($id, $data);

        $this->clearCache();

        return $data;
    }

    // ✅ DELETE
    public function delete($id) {
        $data = $this->menuRepository->delete($id);
        $this->clearCache();
        return $data;
    }

    // ✅ MENU WITH CATEGORY
    public function getMenuData()
    {
        return $this->menuRepository->getMenuWithCategories();
    }

    // ✅ FIND SINGLE
    public function getMenuById($id)
    {
        return $this->menuRepository->find($id);
    }

    // ✅ RELATED MENUS
    public function getRelatedMenus($id)
    {
        $menu = $this->menuRepository->find($id);

        if (!$menu) {
            return [];
        }

        return $this->menuRepository->getRelatedMenus($menu->category_id, $id);
    }

    // ✅ CACHE HANDLER (BEST PRACTICE)
    private function clearCache()
    {
        Cache::forget('home_page_data');
    }
}