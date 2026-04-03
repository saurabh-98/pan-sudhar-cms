<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function all()
    {
        return Category::latest()->get();
    }

    public function create($data)
    {
        return Category::create($data);
    }

    public function update($id, $data)
    {
        return Category::findOrFail($id)->update($data);
    }

    public function delete($id)
    {
        return Category::destroy($id);
    }
}