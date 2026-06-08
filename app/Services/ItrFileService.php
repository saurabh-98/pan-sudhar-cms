<?php

namespace App\Services;

use App\DTO\FileItrDTO;
use App\Models\ItrFile;
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

        $oldPreview = get_itr_session();

        $sessionFiles =
            $oldPreview['files'] ?? [];

        $aadhaarFront =

            $dto->aadhaarFront

                ? $this->storeFile(
                    $dto->aadhaarFront,
                    'itr/aadhaar-front'
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
                    'itr/aadhaar-back'
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
                    'itr/pan-card'
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

                throw new \Exception(
                    'Session expired.'
                );
            }

            $data =
                $session['data'];

            $files =
                $session['files'];

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
                            $data['charge']
                            ??
                            99,

                        'status' =>
                            'pending',
                    ]);

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

            if ($file) {

                delete_uploaded_file(
                    $file
                );
            }
        }

        return $this
            ->itrFileRepository
            ->delete(
                $itrFile
            );
    }
}
