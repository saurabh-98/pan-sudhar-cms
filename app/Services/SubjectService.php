<?php
namespace App\Services;

use App\Repositories\SubjectRepository;
use App\DTO\SubjectDTO;

class SubjectService
{
    public function __construct(protected SubjectRepository $repo) {}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function getAllSubjects()
    {
        return $this->repo->getAll();
    }

    public function create(SubjectDTO $dto)
    {
        return $this->repo->store($dto->toArray());
    }

    public function update($id, SubjectDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}