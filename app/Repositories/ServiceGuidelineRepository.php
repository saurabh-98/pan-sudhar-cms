<?php

namespace App\Repositories;

use App\DTO\ServiceGuidelineDTO;
use App\Models\ServiceGuideline;

class ServiceGuidelineRepository
{
    /**
     * Get Query
     */
    public function query()
    {
        return ServiceGuideline::query();
    }

    /**
     * Get All
     */
    public function getAll()
    {
        return ServiceGuideline::latest()->get();
    }

    /**
     * Datatable Query
     */
    public function getList()
    {
        return ServiceGuideline::query()
            ->latest();
    }

    /**
     * Find By ID
     */
    public function findById(int $id): ServiceGuideline
    {
        return ServiceGuideline::findOrFail($id);
    }

    /**
     * Find Active Guideline by Service Code
     */
    public function findActiveByServiceCode(string $serviceCode): ?ServiceGuideline
    {
        return ServiceGuideline::where('service_code', $serviceCode)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Create
     */
    public function create(ServiceGuidelineDTO $dto): ServiceGuideline
    {
        return ServiceGuideline::create([

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
        ServiceGuideline $guideline,
        ServiceGuidelineDTO $dto
    ): ServiceGuideline {

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
        ServiceGuideline $guideline,
        string $pdf
    ): ServiceGuideline {

        $guideline->update([

            'pdf' => $pdf,

        ]);

        return $guideline;
    }

    /**
     * Delete
     */
    public function delete(ServiceGuideline $guideline): bool
    {
        return $guideline->delete();
    }
}