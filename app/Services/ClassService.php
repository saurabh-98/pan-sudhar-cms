<?php

namespace App\Services;

use App\Repositories\ClassRepository;
use App\DTO\ClassDTO;

class ClassService
{
    protected $repo;

    public function __construct(ClassRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->all();
    }

    public function create(ClassDTO $dto)
    {
        return $this->repo->store($dto->toArray());
    }

    public function update($id, ClassDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}