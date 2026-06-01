<?php

namespace App\Services;

use App\DTO\FileItrDTO;
use App\Models\ItrFile;
use App\Models\User;
use App\Repositories\ItrFileRepository;
use Illuminate\Support\Facades\DB;

class ItrFileService
{
    public function __construct(
        protected ItrFileRepository $itrFileRepository
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
        FileItrDTO $dto
    ): array {

        $oldPreview =
            get_itr_session();

        $existingFiles =
            $oldPreview['files'] ?? [];

        $aadhaarFront =

            $dto->aadhaarFront

                ? $this->storeFile(
                    $dto->aadhaarFront,
                    'itr/aadhaar-front'
                )

                : ($existingFiles['aadhaar_front'] ?? null);

        $aadhaarBack =

            $dto->aadhaarBack

                ? $this->storeFile(
                    $dto->aadhaarBack,
                    'itr/aadhaar-back'
                )

                : ($existingFiles['aadhaar_back'] ?? null);

        $panCard =

            $dto->panCard

                ? $this->storeFile(
                    $dto->panCard,
                    'itr/pan-card'
                )

                : ($existingFiles['pan_card'] ?? null);

        $preview = [

            'data' => [

                'name' =>
                    $dto->name,

                'mobile' =>
                    $dto->mobile,

                'email' =>
                    $dto->email,

                'remarks' =>
                    $dto->remarks,

                'charge' =>
                    $dto->charge,
            ],

            'files' => [

                'aadhaar_front' =>
                    $aadhaarFront,

                'aadhaar_back' =>
                    $aadhaarBack,

                'pan_card' =>
                    $panCard,
            ]
        ];

        save_itr_session(
            $preview
        );

        return $preview;
    }

    /*
    |--------------------------------------------------------------------------
    | FINAL STORE
    |--------------------------------------------------------------------------
    */

    public function storeFromSession(): ItrFile
    {
        return DB::transaction(function () {

            $session =
                get_itr_session();

            if (!$session) {

                abort(
                    404,
                    'Session Expired.'
                );
            }

            $data =
                $session['data'];

            $files =
                $session['files'];

            $user =
                auth()->user();

            $admin =
                User::role('admin')
                    ->first();

            $charge = 99;

            if (
                $user->wallet_balance
                < $charge
            ) {

                throw new \Exception(
                    'Insufficient wallet balance.'
                );
            }

            $beforeBalance =
                $user->wallet_balance;

            $user->wallet_balance -=
                $charge;

            $user->save();

            if ($admin) {

                $admin->wallet_balance +=
                    $charge;

                $admin->save();
            }

            $itrFile =
                $this->itrFileRepository
                    ->create([

                        'user_id' =>
                            auth()->id(),

                        'name' =>
                            $data['name'],

                        'mobile' =>
                            $data['mobile'],

                        'email' =>
                            $data['email'],

                        'remarks' =>
                            $data['remarks'],

                        'aadhaar_front' =>
                            $files['aadhaar_front'],

                        'aadhaar_back' =>
                            $files['aadhaar_back'],

                        'pan_card' =>
                            $files['pan_card'],

                        'charge' =>
                            $charge,

                        'status' =>
                            'pending'
                    ]);

            DB::table(
                'wallet_transactions'
            )->insert([

                'user_id' =>
                    $user->id,

                'receiver_id' =>
                    $admin?->id,

                'amount' =>
                    $charge,

                'type' =>
                    'debit',

                'transaction_type' =>
                    'itr_filing',

                'remarks' =>
                    'ITR filing charge deducted',

                'created_at' =>
                    now(),

                'updated_at' =>
                    now()
            ]);

            if ($admin) {

                DB::table(
                    'wallet_transactions'
                )->insert([

                    'user_id' =>
                        $admin->id,

                    'receiver_id' =>
                        $user->id,

                    'amount' =>
                        $charge,

                    'type' =>
                        'credit',

                    'transaction_type' =>
                        'itr_filing_income',

                    'remarks' =>
                        'ITR filing payment received',

                    'created_at' =>
                        now(),

                    'updated_at' =>
                        now()
                ]);
            }

            clear_itr_session();

            return $itrFile;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(
        int $userId
    ) {
        return $this
            ->itrFileRepository
            ->history($userId);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id,
        int $userId
    ): ItrFile {

        return $this
            ->itrFileRepository
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

        $itrFile =
            $this->find(
                $id,
                $userId
            );

        foreach ([

            $itrFile->aadhaar_front,

            $itrFile->aadhaar_back,

            $itrFile->pan_card

        ] as $file) {

            delete_uploaded_file(
                $file
            );
        }

        return $this
            ->itrFileRepository
            ->delete(
                $itrFile
            );
    }
}