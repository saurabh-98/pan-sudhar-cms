<?php

namespace App\Services;

use App\DTO\TransportStopDTO;
use App\Repositories\TransportStopRepository;

class TransportStopService
{
    public function __construct(
        protected TransportStopRepository $repo
    ) {}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function create(TransportStopDTO $dto)
    {
        return $this->repo->create($dto->toArray());
    }

    public function update($id, TransportStopDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}