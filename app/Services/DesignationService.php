<?php

namespace App\Services;

use App\Repositories\DesignationRepository;
use App\DTO\DesignationDTO;

class DesignationService
{
    public function __construct(
        protected DesignationRepository $repo
    ) {}

    /* ================= GET ALL ================= */
    public function getAll()
    {
        return $this->repo->all();
    }

    /* ================= CREATE ================= */
    public function create(DesignationDTO $dto)
    {
        return $this->repo->create($dto->toArray());
    }

    /* ================= UPDATE ================= */
    public function update(int $id, DesignationDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    /* ================= DELETE ================= */
    public function delete(int $id)
    {
        return $this->repo->delete($id);
    }
}