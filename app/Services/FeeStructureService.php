<?php

namespace App\Services;

use App\Repositories\FeeStructureRepository;
use App\DTO\FeeStructureDTO;

class FeeStructureService
{
    public function __construct(protected FeeStructureRepository $repo){}

    /* ================= GET ALL ================= */
    public function getAll()
    {
        return $this->repo->getAll();
    }

    /* ================= STORE ================= */
    public function store(FeeStructureDTO $dto)
    {
        $data = $dto->toArray();

        // ✅ PREVENT DUPLICATE
        if ($this->repo->exists($data)) {
            throw new \Exception('Fee already exists for this class & type');
        }

        return $this->repo->store($data);
    }

    /* ================= UPDATE ================= */
    public function update($id, FeeStructureDTO $dto)
    {
        $data = $dto->toArray();

        // ✅ PREVENT DUPLICATE (IGNORE CURRENT ID)
        if ($this->repo->exists($data, $id)) {
            throw new \Exception('Fee already exists for this class & type');
        }

        return $this->repo->update($id, $data);
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /* ================= GET FEES BY CLASS ================= */
    public function getFeesByClass($classId)
    {
        $fees = $this->repo->getByClass($classId);

        return [
            'mandatory' => $fees->where('is_mandatory', 1)->values(),
            'optional'  => $fees->where('is_mandatory', 0)->values(),
        ];
    }

    /* ================= CALCULATE TOTAL ================= */
    public function calculateTotal($classId, $selectedFees = [])
    {
        $fees = $this->repo->getByClass($classId);

        $total = 0;

        foreach ($fees as $fee) {

            // Mandatory always included
            if ($fee->is_mandatory) {
                $total += $fee->amount;
            }

            // Optional selected
            if (!$fee->is_mandatory && isset($selectedFees[$fee->fee_type])) {
                $total += $fee->amount;
            }
        }

        return $total;
    }
}