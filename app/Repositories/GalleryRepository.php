<?php
namespace App\Repositories;

use App\Models\Gallery;
use App\DTO\GalleryDTO;

class GalleryRepository
{
    public function getAll()
    {
        return Gallery::latest()->get();
    }

    public function store(GalleryDTO $dto)
    {
        $filePath = null;

        // PHOTO
        if ($dto->type === 'photo') {

            $file = $dto->file; // ✔ FIXED (no file())

            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/gallery'), $fileName);

            $filePath = $fileName;

        } 
        // VIDEO
        else {
            $filePath = $dto->file;
        }

        return Gallery::create([
            'title' => $dto->title,
            'type'  => $dto->type,
            'file'  => $filePath
        ]);
    }

    public function update(GalleryDTO $dto, $id)
    {
        $gallery = Gallery::findOrFail($id);

        // PHOTO UPDATE
        if ($dto->type === 'photo' && $dto->file) {

            // delete old file
            if ($gallery->file && file_exists(public_path('uploads/gallery/'.$gallery->file))) {
                unlink(public_path('uploads/gallery/'.$gallery->file));
            }

            $file = $dto->file;

            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $file->move(public_path('uploads/gallery'), $fileName);

            $gallery->file = $fileName;
        }

        // VIDEO UPDATE
        if ($dto->type === 'video') {
            $gallery->file = $dto->file;
        }

        $gallery->title = $dto->title;
        $gallery->type  = $dto->type;

        $gallery->save();

        return $gallery;
    }

    public function delete($id)
    {
        $gallery = Gallery::findOrFail($id);

        if ($gallery->type === 'photo') {
            $path = public_path('uploads/gallery/'.$gallery->file);

            if (file_exists($path)) {
                unlink($path);
            }
        }

        return $gallery->delete();
    }
}