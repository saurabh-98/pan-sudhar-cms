<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\DTO\OtherServiceDTO;
use App\Models\OtherService;
use App\Repositories\OtherServiceRepository;

class OtherServiceService
{
    public function __construct(
        protected OtherServiceRepository $repository
    ) {}

    protected function storeFile(
        $file,
        string $path
    ): ?string {

        if (! $file) {
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
        OtherServiceDTO $dto
    ): array {

        $oldPreview =
            get_other_service_session();

        $existingFiles =
            $oldPreview['files'] ?? [];

        $storedFiles = [];

        foreach (
            $dto->files as $field => $file
        ) {

            $storedFiles[$field] =
                $this->storeFile(
                    $file,
                    'other-services/' . $dto->service_slug
                );
        }

        foreach (
            $existingFiles as $field => $path
        ) {

            if (
                ! isset($storedFiles[$field])
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

        save_other_service_session(
            $previewData
        );

        return $previewData;
    }

    /*
    |--------------------------------------------------------------------------
    | STORE FROM SESSION
    |--------------------------------------------------------------------------
    */

    public function storeFromSession(): OtherService
    {
        return DB::transaction(function () {

            $session =
                get_other_service_session();

            if (! $session) {

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

            $charge =
                $data['other_service_charge']
                ?? 0;

            unset(
                $data['other_service_charge']
            );

            $storeData = [

                'user_id' =>
                    auth()->id(),

                'application_no' =>
                    'OTH'
                    . now()->format('YmdHis')
                    . rand(1000, 9999),

                'service_name' =>
                    $data['service_name'],

                'service_slug' =>
                    $data['service_slug'],

                'form_data' =>
                    $data['form_data'],

                'documents' =>
                    $documents,

                'amount' =>
                    $charge,

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

            clear_other_service_session();

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
    ): OtherService {

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