<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\Category;

class MenuRepository {

    // ✅ GET ALL
    public function all() {
        return Menu::with('category')
            ->orderBy('id', 'desc')
            ->get();
    }

    // ✅ CREATE
    public function create(array $data) {
        return Menu::create($data); // includes description & specifications automatically
    }

    // ✅ FIND
    public function find($id) {
        return Menu::findOrFail($id);
    }

    // ✅ MENU WITH CATEGORY (HOME PAGE)
    public function getMenuWithCategories()
    {
        return Category::with(['menus' => function ($q) {
            $q->where('is_available', 1);
        }])->get();
    }

    // ✅ UPDATE
    public function update($id, array $data) {
        $menu = $this->find($id);
        $menu->update($data);
        return $menu;
    }

    // ✅ DELETE
    public function delete($id) {
        return Menu::destroy($id);
    }

    // ✅ FIXED RELATED MENUS (OPTIMIZED)
    public function getRelatedMenus($categoryId, $excludeId)
    {
        return Menu::where('category_id', $categoryId)
                    ->where('id', '!=', $excludeId)
                    ->where('is_available', 1)
                    ->get();
    }
}