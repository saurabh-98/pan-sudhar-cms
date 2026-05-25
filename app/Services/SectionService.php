<?php

namespace App\Services;

use App\Repositories\SectionRepository;
use App\DTO\SectionDTO;

class SectionService
{
    public function __construct(protected SectionRepository $sectionRepository) {}

    /* ================= ALL ================= */
    public function getAll()
    {
        return $this->sectionRepository->all();
    }

    /* ================= CREATE ================= */
    public function create(SectionDTO $dto)
    {
        $data = $dto->toArray();

        // ✅ normalize name (A, B, C)
        $data['name'] = strtoupper($data['name']);

        return $this->sectionRepository->store($data);
    }

    /* ================= UPDATE ================= */
    public function update($id, SectionDTO $dto)
    {
        $data = $dto->toArray();

        // ✅ normalize
        $data['name'] = strtoupper($data['name']);

        return $this->sectionRepository->update($id, $data);
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        return $this->sectionRepository->delete($id);
    }

    /* ================= GET BY CLASS ================= */
    public function getByClass($class_id)
    {
        return $this->sectionRepository->getByClass($class_id);
    }

    /* ================= GET WITH COUNT ================= */
    public function getByClassWithCount($class_id)
    {
        return $this->sectionRepository->getByClassWithCount($class_id);
    }
}