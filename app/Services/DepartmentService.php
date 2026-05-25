<?php
namespace App\Services;

use App\DTO\DepartmentDTO;
use App\Repositories\DepartmentRepository;

class DepartmentService
{
    public function __construct(protected DepartmentRepository $repo){}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function create(DepartmentDTO $dto)
    {
        return $this->repo->store($dto->toArray());
    }

    public function update($id, DepartmentDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}