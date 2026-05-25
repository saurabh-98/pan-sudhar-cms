<?php
namespace App\Services;

use App\DTO\EmployeeDTO;
use App\Repositories\EmployeeRepository;

class EmployeeService
{
    public function __construct(protected EmployeeRepository $repo){}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function create(EmployeeDTO $dto)
    {
        return $this->repo->store($dto->toArray());
    }

    public function update($id,EmployeeDTO $dto)
    {
        return $this->repo->update($id,$dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}