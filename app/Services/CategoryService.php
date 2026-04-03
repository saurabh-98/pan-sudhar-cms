<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\DTO\CategoryDTO;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache; 


class CategoryService {

    protected $repo;

    public function __construct(CategoryRepository $repo) {
        $this->repo = $repo;
    }

    public function getAll() {
        $data = $this->repo->all();
         Cache::forget('home_page_data');

        return $data;
    }

    public function store(CategoryDTO $dto) {

        $imagePath = null;

        if ($dto->image && $dto->image->isValid()) {

            $filename = time().'_'.Str::random(10).'.'.$dto->image->getClientOriginalExtension();

            // ✅ FORCE PUBLIC DISK
            Storage::disk('public')->putFileAs(
                'categories',
                $dto->image,
                $filename
            );

            $imagePath = 'categories/'.$filename;
        }

        $data =  $this->repo->create([
            'name' => $dto->name,
            'image' => $imagePath
        ]);

         Cache::forget('home_page_data');

         return $data;
    }

    public function update($id, CategoryDTO $dto) {

        $data = [
            'name' => $dto->name
        ];

        if ($dto->image && $dto->image->isValid()) {

            $filename = time().'_'.Str::random(10).'.'.$dto->image->getClientOriginalExtension();

            // ✅ FORCE PUBLIC DISK
            Storage::disk('public')->putFileAs(
                'categories',
                $dto->image,
                $filename
            );

            $data['image'] = 'categories/'.$filename;
        }

        $data = $this->repo->update($id, $data);
         Cache::forget('home_page_data');
         return $data;
    }

    public function delete($id) {
        $data = $this->repo->delete($id);
         Cache::forget('home_page_data');
         return $data;
    }
}