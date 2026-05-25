<?php
namespace App\Services;

use App\Repositories\TimetableRepository;
use App\DTO\TimetableDTO;

class TimetableService
{
    public function __construct(protected TimetableRepository $repo) {}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function create(TimetableDTO $dto)
    {
        return $this->repo->store($dto->toArray());
    }

    public function update($id, TimetableDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}