<?php

namespace App\Services;

use App\DTO\BankDocsDTO;
use App\Models\BankDocs;
use App\Repositories\BankDocsRepository;
use Illuminate\Support\Facades\DB;

class BankDocsService
{
    public function __construct(
        protected BankDocsRepository $repository
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
    public function find(int $id): BankDocs
    {
        return $this->repository->findById($id);
    }

    /**
     * Store Guideline
     */
    public function store(BankDocsDTO $dto): BankDocs
    {
        return DB::transaction(function () use ($dto) {

            $guideline = $this->repository->create($dto);

            if ($dto->pdf) {

                $path = store_uploaded_file(
                    $dto->pdf,
                    'bank-docs'
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
        BankDocsDTO $dto
    ): BankDocs {

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
                    'bank-docs'
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
    ): ?BankDocs {

        return $this->repository
            ->findActiveByServiceCode(
                $serviceCode
            );
    }
}