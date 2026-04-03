<?php
namespace App\Repositories;

use App\Models\Page;

class PageRepository
{
    public function getAll()
    {
        return Page::latest()->get();
    }

    public function find($id)
    {
        return Page::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Page::where('slug',$slug)->where('status',1)->firstOrFail();
    }

    public function create($data)
    {
        return Page::create($data);
    }

    public function update($id,$data)
    {
        $page = $this->find($id);
        $page->update($data);
        return $page;
    }

    public function delete($id)
    {
        return Page::destroy($id);
    }
}