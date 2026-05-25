<?php
namespace App\Services;

use App\Repositories\MarkRepository;
use App\DTO\MarkDTO;

class MarkService
{
    public function __construct(protected MarkRepository $repo){}

    public function getAll()
    {
        return $this->repo->getAll(); // ✅ fixed
    }

    public function store(MarkDTO $dto)
    {
        return $this->repo->store($dto->toArray());
    }

    public function update($id, MarkDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }
    

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}