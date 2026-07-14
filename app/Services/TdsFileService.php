<?php

namespace App\Services;

use App\DTO\FileTdsDTO;
use App\Models\TdsFile;
use App\Repositories\TdsFileRepository;
use Illuminate\Support\Facades\DB;

class TdsFileService
{
    public function __construct(
        protected TdsFileRepository $tdsFileRepository
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
        FileTdsDTO $dto
    ): array {

        $oldPreview = get_tds_session();

        $sessionFiles =
            $oldPreview['files'] ?? [];

        $aadhaarFront =

            $dto->aadhaarFront

                ? $this->storeFile(
                    $dto->aadhaarFront,
                    'tds/aadhaar-front'
                )

                : (
                    $dto->existingAadhaarFront
                    ??
                    $sessionFiles['aadhaar_front']
                    ??
                    null
                );

        $aadhaarBack =

            $dto->aadhaarBack

                ? $this->storeFile(
                    $dto->aadhaarBack,
                    'tds/aadhaar-back'
                )

                : (
                    $dto->existingAadhaarBack
                    ??
                    $sessionFiles['aadhaar_back']
                    ??
                    null
                );

        $panCard =

            $dto->panCard

                ? $this->storeFile(
                    $dto->panCard,
                    'tds/pan-card'
                )

                : (
                    $dto->existingPanCard
                    ??
                    $sessionFiles['pan_card']
                    ??
                    null
                );

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

        save_tds_session(
            $preview
        );

        return $preview;
    }

    /*
    |--------------------------------------------------------------------------
    | FINAL STORE
    |--------------------------------------------------------------------------
    */

    public function storeFromSession(): TdsFile
    {
        return DB::transaction(function () {

            $session =
                get_tds_session();

            if (!$session) {

                throw new \Exception(
                    'Session expired.'
                );
            }

            $data =
                $session['data'];

            $files =
                $session['files'];

            $tdsFile =
                $this->tdsFileRepository
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
                            $data['charge']
                            ??
                            99,

                        'status' =>
                            'pending',
                    ]);

            clear_tds_session();

            return $tdsFile;
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
            ->tdsFileRepository
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
    ): TdsFile {

        return $this
            ->tdsFileRepository
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

        $tdsFile =
            $this->find(
                $id,
                $userId
            );

        foreach ([

            $tdsFile->aadhaar_front,

            $tdsFile->aadhaar_back,

            $tdsFile->pan_card

        ] as $file) {

            if ($file) {

                delete_uploaded_file(
                    $file
                );
            }
        }

        return $this
            ->tdsFileRepository
            ->delete(
                $tdsFile
            );
    }
}