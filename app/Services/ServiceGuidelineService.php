<?php

namespace App\Services;

use App\DTO\ServiceGuidelineDTO;
use App\Models\ServiceGuideline;
use App\Repositories\ServiceGuidelineRepository;
use Illuminate\Support\Facades\DB;

class ServiceGuidelineService
{
    public function __construct(
        protected ServiceGuidelineRepository $repository
    ) {}

    /**
     * Datatable List
     */
    public function getList()
    {
        return $this->repository->getList();
    }

    /**
     * Find By ID
     */
    public function find(int $id): ServiceGuideline
    {
        return $this->repository->findById($id);
    }

    /**
     * Store Guideline
     */
    public function store(ServiceGuidelineDTO $dto): ServiceGuideline
    {
        return DB::transaction(function () use ($dto) {

            $guideline = $this->repository->create($dto);

            if ($dto->pdf) {

                $path = store_uploaded_file(
                    $dto->pdf,
                    'service-guidelines'
                );

                $this->repository->updatePdf(
                    $guideline,
                    $path
                );
            }

            return $guideline;
        });
    }

    /**
     * Update Guideline
     */
    public function update(
        int $id,
        ServiceGuidelineDTO $dto
    ): ServiceGuideline {

        return DB::transaction(function () use ($id, $dto) {

            $guideline = $this->repository->findById($id);

            $this->repository->update(
                $guideline,
                $dto
            );

            if ($dto->pdf) {

                /*
                |--------------------------------------------------------------------------
                | Delete Old PDF
                |--------------------------------------------------------------------------
                */

                if (
                    $guideline->pdf &&
                    file_exists_custom($guideline->pdf)
                ) {
                    delete_uploaded_file(
                        $guideline->pdf
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Upload New PDF
                |--------------------------------------------------------------------------
                */

                $path = store_uploaded_file(
                    $dto->pdf,
                    'service-guidelines'
                );

                $this->repository->updatePdf(
                    $guideline,
                    $path
                );
            }

            return $guideline->fresh();
        });
    }

    /**
     * Delete Guideline
     */
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            $guideline = $this->repository->findById($id);

            if (
                $guideline->pdf &&
                file_exists_custom($guideline->pdf)
            ) {

                delete_uploaded_file(
                    $guideline->pdf
                );
            }

            return $this->repository->delete(
                $guideline
            );
        });
    }

    /**
     * Get Active Guideline
     */
    public function getActiveGuideline(
        string $serviceCode
    ): ?ServiceGuideline {

        return $this->repository
            ->findActiveByServiceCode(
                $serviceCode
            );
    }
}