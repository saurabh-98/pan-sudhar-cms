<?php
namespace App\Services;

use App\Repositories\GalleryRepository;
use App\DTO\GalleryDTO;

class GalleryService
{
    protected $repo;

    public function __construct(GalleryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function store(GalleryDTO $dto)
    {
        return $this->repo->store($dto);
    }

    public function update(GalleryDTO $dto, $id)
    {
        return $this->repo->update($dto, $id);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}