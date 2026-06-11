<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\DTO\AadhaarServiceDTO;
use App\Models\AadhaarService;
use App\Repositories\AadhaarServiceRepository;

class AadhaarServiceService
{
    public function __construct(
        protected AadhaarServiceRepository $repository
    ) {}

    /*
    |--------------------------------------------------------------------------
    | STORE FILE
    |--------------------------------------------------------------------------
    */

    protected function storeFile(
        $file,
        string $path
    ): ?string {

        if (!$file) {
            return null;
        }

        return store_uploaded_file(
            $file,
            $path
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

    public function preview(
        AadhaarServiceDTO $dto
    ): array {

        $oldPreview =
            get_aadhaar_session();

        $existingFiles =
            $oldPreview['files'] ?? [];

        $storedFiles = [];

        foreach (
            $dto->files as $field => $file
        ) {

            $storedFiles[$field] =
                $this->storeFile(
                    $file,
                    'aadhaar/' . $dto->service_slug
                );
        }

        foreach (
            $existingFiles as $field => $path
        ) {

            if (
                !isset($storedFiles[$field])
            ) {

                $storedFiles[$field] =
                    $path;
            }
        }

        $previewData = [

            'data' => [

                'service_name' =>

                    $dto->service_name,

                'service_slug' =>

                    $dto->service_slug,

                'form_data' =>

                    $dto->form_data,

            ],

            'files' =>

                $storedFiles,
        ];

        save_aadhaar_session(
            $previewData
        );

        return $previewData;
    }

    /*
    |--------------------------------------------------------------------------
    | STORE FROM SESSION
    |--------------------------------------------------------------------------
    */

    public function storeFromSession(): AadhaarService
    {
        return DB::transaction(function () {

            $session =
                get_aadhaar_session();

            if (!$session) {

                abort(
                    404,
                    'Session Expired.'
                );
            }

            $data =
                $session['data']
                ?? [];

            $documents =
                $session['files']
                ?? [];

            $aadhaarCharge =
                $data['aadhaar_charge']
                ?? 0;

            unset(
                $data['aadhaar_charge']
            );

            $storeData = [

                'user_id' =>

                    auth()->id(),

                'application_no' =>

                    'AAD'
                    . now()->format('YmdHis')
                    . rand(1000,9999),

                'service_name' =>

                    $data['service_name'],

                'service_slug' =>

                    $data['service_slug'],

                'form_data' =>

                    $data['form_data'],

                'documents' =>

                    $documents,

                'amount' =>

                    $aadhaarCharge,

                'payment_status' =>

                    'Pending',

                'status' =>

                    'Pending',

                'wallet_deducted' =>

                    false,

                'wallet_deducted_at' =>

                    null,

                'ip_address' =>

                    request()->ip(),

                'browser' =>

                    request()->userAgent(),
            ];

            $application =

                $this->repository
                    ->create(
                        $storeData
                    );

            clear_aadhaar_session();

            return $application;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(
        int $userId
    )
    {
        return $this->repository
            ->history(
                $userId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id,
        int $userId
    ): AadhaarService {

        return $this->repository
            ->findByUser(
                $id,
                $userId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        int $id,
        int $userId
    ): bool {

        $application =
            $this->find(
                $id,
                $userId
            );

        foreach (
            $application->documents ?? []
            as $file
        ) {

            delete_uploaded_file(
                $file
            );
        }

        return $this->repository
            ->delete(
                $application
            );
    }
}
