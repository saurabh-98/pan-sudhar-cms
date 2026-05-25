<?php
// app/Services/TransportRouteService.php

namespace App\Services;

use App\DTO\TransportRouteDTO;
use App\Repositories\TransportRouteRepository;

class TransportRouteService
{
    public function __construct(
        protected TransportRouteRepository $repo
    ) {}

    public function create(TransportRouteDTO $dto)
    {
        return $this->repo->create($dto->toArray());
    }

    public function getAll()
    {
        return $this->repo->all();
    }

    public function getAllWithRelations()
    {
        return $this->repo->allWithRelations();
    }
    
    public function update($id, TransportRouteDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}