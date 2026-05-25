<?php

namespace App\Repositories;

use App\Models\FeeStructure;

class FeeStructureRepository
{
    /* ================= BASE QUERY ================= */
    private function baseQuery()
    {
        return FeeStructure::query();
    }

    /* ================= GET ALL ================= */
    public function getAll()
    {
        return $this->baseQuery()
            ->with('class:id,name')
            ->select(
                'id',
                'class_id',
                'fee_type',
                'amount',
                'academic_year',
                'is_mandatory',
                'fee_category',
                'frequency'
            )
            ->latest()
            ->paginate(20);
    }

    /* ================= STORE ================= */
    public function store(array $data)
    {
        return FeeStructure::create($data);
    }

    /* ================= UPDATE ================= */
    public function update($id, array $data)
    {
        $fee = $this->find($id);
        $fee->update($data);
        return $fee;
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        return FeeStructure::destroy($id);
    }

    /* ================= FIND ================= */
    public function find($id)
    {
        return FeeStructure::findOrFail($id);
    }

    /* ================= EXISTS ================= */
    public function exists(array $data, $ignoreId = null)
    {
        $query = $this->baseQuery()->where([
            'class_id' => $data['class_id'],
            'fee_type' => $data['fee_type'],
            'academic_year' => $data['academic_year']
        ]);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    /* ================= GET BY CLASS ================= */
    public function getByClass($classId)
    {
        return $this->baseQuery()
            ->where('class_id', $classId)
            ->get();
    }

    /* ================= GET MANDATORY ================= */
    public function getMandatoryFees($classId)
    {
        return $this->baseQuery()
            ->where('class_id', $classId)
            ->where('is_mandatory', 1)
            ->get();
    }

    /* ================= GET OPTIONAL ================= */
    public function getOptionalFees($classId)
    {
        return $this->baseQuery()
            ->where('class_id', $classId)
            ->where('is_mandatory', 0)
            ->get();
    }
}