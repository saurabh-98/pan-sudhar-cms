<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use App\Services\CategoryService;
use App\DTO\MenuDTO;
use App\Http\Requests\MenuRequest;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $menuService;
    protected $categoryService;

    public function __construct(MenuService $menuService, CategoryService $categoryService)
    {
        $this->menuService = $menuService;
        $this->categoryService = $categoryService;
    }

    
    public function index()
    {
        $categories = $this->categoryService->getAll();
        return view('admin.menus.index', compact('categories'));
    }

 
    public function list()
    {
        return response()->json([
            'data' => $this->menuService->getAll()
        ]);
    }

    public function store(MenuRequest $request)
    {
        try {
            $dto = new MenuDTO($request);

            $this->menuService->store($dto);

            return response()->json([
                'success' => true,
                'message' => 'Menu added successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
    public function update(MenuRequest $request, $id)
    {
        try {
            $dto = new MenuDTO($request);

            $this->menuService->update($id, $dto);

            return response()->json([
                'success' => true,
                'message' => 'Menu updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    public function delete($id)
    {
        try {
            $this->menuService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Menu deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
    public function show($id)
    {
        try {
            $menu = $this->menuService->getMenuById($id);

            return response()->json([
                'success' => true,
                'data' => $menu
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu not found'
            ], 404);
        }
    }
}