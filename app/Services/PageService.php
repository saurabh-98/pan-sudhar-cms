<?php
namespace App\Services;

use App\Repositories\PageRepository;

class PageService
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function list()
    {
        return $this->pageRepository->getAll();
    }

    public function store($data)
    {
        return $this->pageRepository->create($data);
    }

    public function update($id,$data)
    {
        return $this->pageRepository->update($id,$data);
    }

    public function delete($id)
    {
        return $this->pageRepository->delete($id);
    }

    public function getBySlug($slug)
    {
        return $this->pageRepository->findBySlug($slug);
    }
}