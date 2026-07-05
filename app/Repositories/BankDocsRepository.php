<?php

namespace App\Repositories;

use App\DTO\BankDocsDTO;
use App\Models\BankDocs;

class BankDocsRepository
{
    /**
     * Get Query
     */
    public function query()
    {
        return BankDocs::query();
    }

    /**
     * Get All
     */
    public function getAll()
    {
        return BankDocs::latest()->get();
    }

    /**
     * Datatable Query
     */
    public function getList()
    {
        return BankDocs::query()
            ->latest();
    }

    /**
     * Find By ID
     */
    public function findById(int $id): BankDocs
    {
        return BankDocs::findOrFail($id);
    }

    /**
     * Find Active Guideline by Service Code
     */
    public function findActiveByServiceCode(string $serviceCode): ?BankDocs
    {
        return BankDocs::where('service_code', $serviceCode)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Create
     */
    public function create(BankDocsDTO $dto): BankDocs
    {
        return BankDocs::create([

            'service_code' => $dto->service_code,

            'title' => $dto->title,

            'description' => $dto->description,

            'pdf' => '',

            'is_active' => $dto->is_active,

        ]);
    }

    /**
     * Update
     */
    public function update(
        BankDocs $guideline,
        BankDocsDTO $dto
    ): BankDocs {

        $guideline->update([

            'service_code' => $dto->service_code,

            'title' => $dto->title,

            'description' => $dto->description,

            'is_active' => $dto->is_active,

        ]);

        return $guideline;
    }

    /**
     * Update PDF
     */
    public function updatePdf(
        BankDocs $guideline,
        string $pdf
    ): BankDocs {

        $guideline->update([

            'pdf' => $pdf,

        ]);

        return $guideline;
    }

    /**
     * Delete
     */
    public function delete(BankDocs $guideline): bool
    {
        return $guideline->delete();
    }
}