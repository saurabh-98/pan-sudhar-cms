<?php

namespace App\Services;

use App\DTO\BannerDTO;
use App\Repositories\BannerRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache; // ✅ ADDED

class BannerService
{
    protected $repo;

    public function __construct(BannerRepository $repo)
    {
        $this->repo = $repo;
    }

    /* ================= GET ================= */
    public function getAll()
    {
        return $this->repo->getAll();
    }

    /* ================= STORE ================= */
    public function store(BannerDTO $dto, $files = [])
    {
        $images = [];

        foreach ($files ?? [] as $file) {

            if ($file && $file->isValid()) {

                $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

                // ✅ save to storage/app/public/banners
                Storage::disk('public')->putFileAs(
                    'banners',
                    $file,
                    $filename
                );

                // ✅ clean path
                $images[] = str_replace('\\', '/', 'banners/'.$filename);
            }
        }

        $dto->image = $images;

        $result = $this->repo->create($dto->toArray());

        // ✅ CLEAR CACHE (CRITICAL)
        Cache::forget('home_page_data');

        return $result;
    }

    /* ================= UPDATE ================= */
    public function update($id, BannerDTO $dto, $files = [])
    {
        $banner = $this->repo->find($id);

        if (!$banner) {
            return false;
        }

        // Existing images from request
        $existingImages = request()->input('existing_images', []);

        // Convert to array if needed
        if (is_string($existingImages)) {

            $decoded = json_decode($existingImages, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $existingImages = $decoded;
            } else {
                $existingImages = [$existingImages];
            }
        }

        if (!is_array($existingImages)) {
            $existingImages = [];
        }

        // Images from database
        $oldImages = $banner->image ?? [];

        if (is_string($oldImages)) {

            $decoded = json_decode($oldImages, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $oldImages = $decoded;
            } else {
                $oldImages = [$oldImages];
            }
        }

        if (!is_array($oldImages)) {
            $oldImages = [];
        }

        // Keep existing images if user didn't remove them
        $images = !empty($existingImages) ? $existingImages : $oldImages;

        // Upload new images
        $newImages = [];

        foreach ($files ?? [] as $file) {

            if ($file && $file->isValid()) {

                $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

                Storage::disk('public')->putFileAs(
                    'banners',
                    $file,
                    $filename
                );

                $newImages[] = 'banners/'.$filename;
            }
        }

        // Merge arrays safely
        $dto->image = array_merge($images, $newImages);

        $result = $this->repo->update($banner, $dto->toArray());

        Cache::forget('home_page_data');

        return $result;
    }

    /* ================= DELETE ================= */
    public function deleteById($id)
    {
        $banner = $this->repo->find($id);

        if (!$banner) {
            return false;
        }

        $images = $banner->image ?? [];

        foreach ($images as $img) {
            if (Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
        }

        $result = $this->repo->delete($banner);

        // ✅ CLEAR CACHE
        Cache::forget('home_page_data');

        return $result;
    }
}