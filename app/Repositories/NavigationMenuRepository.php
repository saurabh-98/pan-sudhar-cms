<?php
namespace App\Repositories;

use App\Models\NavigationMenu;

class NavigationMenuRepository
{
    public function getAll()
    {
        return NavigationMenu::orderBy('order')->get();
    }



    public function find($id)
    {
        return NavigationMenu::findOrFail($id);
    }

    public function create(array $data)
    {
        return NavigationMenu::create($data);
    }

    public function update($id, array $data)
    {
        $menu = $this->find($id);
        $menu->update($data);
        return $menu;
    }

    public function delete($id)
    {
        return NavigationMenu::destroy($id);
    }
}