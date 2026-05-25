<?php
namespace App\Services;

use App\Repositories\ParentRepository;
use App\DTO\ParentDTO;

class ParentService
{
    public function __construct(protected ParentRepository $repo){}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function create(ParentDTO $dto)
    {
        return $this->repo->store($dto->toArray());
    }

    public function update($id, ParentDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}