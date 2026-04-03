<?php
namespace App\Repositories;

use App\Models\News;

class NewsRepository
{
    public function getAll()
    {
        return News::latest()->get();
    }

    public function create(array $data)
    {
        return News::create($data);
    }

    public function update(News $news, array $data)
    {
        return $news->update($data);
    }

    public function delete(News $news)
    {
        return $news->delete();
    }
}