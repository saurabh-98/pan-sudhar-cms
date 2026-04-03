<?php

namespace App\Services;

use App\DTO\NewsDTO;
use App\Models\News;
use App\Repositories\NewsRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache; 


class NewsService
{
    protected $repo;

    public function __construct(NewsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function store(NewsDTO $dto, $file = null)
    {
        if ($file && $file->isValid()) {

            $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

            // ✅ FORCE PUBLIC DISK
            Storage::disk('public')->putFileAs(
                'news',
                $file,
                $filename
            );

            $dto->image = 'news/'.$filename;
        }

        $data = $this->repo->create($dto->toArray());
         Cache::forget('home_page_data');

        return $data;
    }

    public function update(News $news, NewsDTO $dto, $file = null)
    {
        if ($file && $file->isValid()) {

            // ✅ delete old image safely
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }

            $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

            // ✅ FORCE PUBLIC DISK
            Storage::disk('public')->putFileAs(
                'news',
                $file,
                $filename
            );

            $dto->image = 'news/'.$filename;
        }

        $data = $this->repo->update($news, $dto->toArray());
         Cache::forget('home_page_data');

        return $data;
    }

    public function delete(News $news)
    {
        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $data = $this->repo->delete($news);
         Cache::forget('home_page_data');

        return $data;
    }
}