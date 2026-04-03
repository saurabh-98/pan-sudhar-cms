<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NavigationMenuService;
use App\Http\Requests\NavigationMenuRequest;
use App\DTO\NavigationMenuDTO;

class NavigationMenuController extends Controller
{
    protected $navigationService;

    public function __construct(NavigationMenuService $navigationService)
    {
        $this->navigationService = $navigationService;
    }

    public function index()
    {
        $menus = $this->navigationService->getAllMenus();
        return view('admin.navigation.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.navigation.create');
    }

    public function store(NavigationMenuRequest $request)
    {
        $dto = NavigationMenuDTO::fromRequest($request);

        $this->navigationService->createMenu($dto);

        return redirect()->route('admin.navigation.index')
                         ->with('success', 'Created');
    }

     public function list()
    {
        return response()->json($this->navigationService->list());
    }

    public function edit($id)
    {
        $menu = $this->navigationService->findMenu($id);
        return view('admin.navigation.edit', compact('menu'));
    }

    public function update(NavigationMenuRequest $request, $id)
    {
        $dto = NavigationMenuDTO::fromRequest($request);

        $this->navigationService->updateMenu($id, $dto);

        return redirect()->route('admin.navigation.index')
                         ->with('success', 'Updated');
    }

    public function destroy($id)
    {
        $this->navigationService->deleteMenu($id);

        return back()->with('success', 'Deleted');
    }
}