<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\DTO\BankAccountServiceDTO;
use App\Models\BankAccountService;
use App\Repositories\BankAccountServiceRepository;

class BankAccountServiceService
{
    public function __construct(
        protected BankAccountServiceRepository $repository
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

    public function preview(
        BankAccountServiceDTO $dto
    ): array {

        $oldPreview =
            get_bank_session();

        $existingFiles =
            $oldPreview['files'] ?? [];

        $storedFiles = [];

        foreach (
            $dto->files as $field => $file
        ) {

            $storedFiles[$field] =
                $this->storeFile(
                    $file,
                    'bank-account/' . $dto->service_slug
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

        save_bank_session(
            $previewData
        );

        return $previewData;
    }

    public function storeFromSession(): BankAccountService
    {
        return DB::transaction(function () {

            $session =
                get_bank_session();

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
                $data['bank_charge']
                ?? 0;

            unset(
                $data['bank_charge']
            );

            $storeData = [

                'user_id' =>
                    auth()->id(),

                'application_no' =>
                    'BANK'
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

            clear_bank_session();

            return $application;
        });
    }

    public function history(
        int $userId
    )
    {
        return $this->repository
            ->history(
                $userId
            );
    }

    public function find(
        int $id,
        int $userId
    ): BankAccountService {

        return $this->repository
            ->findByUser(
                $id,
                $userId
            );
    }

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