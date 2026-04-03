<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\DTO\CategoryDTO;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    protected $categoryService;

       public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

  
    public function index()
    {
        return view('admin.categories.index');
    }

   
    public function list()
    {
        return response()->json(
            $this->categoryService->getAll()
        );
    }

    
    public function store(CategoryRequest $request)
    {
        $dto = new CategoryDTO($request);

        $this->categoryService->store($dto);

        return response()->json([
            'success' => true,
            'message' => 'Category added successfully'
        ]);
    }

    public function update(CategoryRequest $request, $id)
    {
        $dto = new CategoryDTO($request);

        $this->categoryService->update($id, $dto);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully'
        ]);
    }

    
    public function delete($id)
    {
        $this->categoryService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}