<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\DTO\PanApplicationDTO;
use App\Models\PanApplication;
use App\Repositories\PanApplicationRepository;

class PanApplicationService
{
    public function __construct(

        protected PanApplicationRepository $repository

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
    | PREVIEW SESSION
    |--------------------------------------------------------------------------
    */

    public function preview(
        PanApplicationDTO $dto
    ): array {

        /*
        |--------------------------------------------------------------------------
        | ENSURE FOLDERS
        |--------------------------------------------------------------------------
        */

        ensure_upload_directories();

        /*
        |--------------------------------------------------------------------------
        | EXISTING FILES
        |--------------------------------------------------------------------------
        */

        $existingFiles = session(
            'pan_application.files',
            []
        );

        /*
        |--------------------------------------------------------------------------
        | PHOTO
        |--------------------------------------------------------------------------
        */

        $photoPath =

            $dto->photo

                ? $this->storeFile(
                    $dto->photo,
                    'pan/photo'
                )

                : ($existingFiles['photo'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | SIGNATURE
        |--------------------------------------------------------------------------
        */

        $signaturePath =

            $dto->signature

                ? $this->storeFile(
                    $dto->signature,
                    'pan/signature'
                )

                : ($existingFiles['signature'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | AADHAAR CARD
        |--------------------------------------------------------------------------
        */

        $aadhaarCardPath =

            $dto->aadhaar_card

                ? $this->storeFile(
                    $dto->aadhaar_card,
                    'pan/aadhaar'
                )

                : ($existingFiles['aadhaar_card'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | DOB PROOF
        |--------------------------------------------------------------------------
        */

        $dobProofFilePath =

            $dto->dob_proof_file

                ? $this->storeFile(
                    $dto->dob_proof_file,
                    'pan/dob-proof'
                )

                : ($existingFiles['dob_proof_file'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | SUPPORTING DOCUMENT
        |--------------------------------------------------------------------------
        */

        $supportingDocumentPath =

            $dto->supporting_document

                ? $this->storeFile(
                    $dto->supporting_document,
                    'pan/document'
                )

                : ($existingFiles['supporting_document'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | SESSION DATA
        |--------------------------------------------------------------------------
        */

        $previewData = [

            'data' =>

                $dto->previewArray(),

            'files' => [

                'photo' =>
                    $photoPath,

                'signature' =>
                    $signaturePath,

                'aadhaar_card' =>
                    $aadhaarCardPath,

                'dob_proof_file' =>
                    $dobProofFilePath,

                'supporting_document' =>
                    $supportingDocumentPath

            ]

        ];

        /*
        |--------------------------------------------------------------------------
        | STORE SESSION
        |--------------------------------------------------------------------------
        */

        session([

            'pan_application' => $previewData

        ]);

        /*
        |--------------------------------------------------------------------------
        | FORCE SAVE SESSION
        |--------------------------------------------------------------------------
        */

        request()->session()->save();

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return $previewData;
    }

    /*
    |--------------------------------------------------------------------------
    | STORE APPLICATION
    |--------------------------------------------------------------------------
    */

    public function storeFromSession(): PanApplication
    {
        return DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | SESSION
            |--------------------------------------------------------------------------
            */

            $session =
                session('pan_application');

            /*
            |--------------------------------------------------------------------------
            | CHECK SESSION
            |--------------------------------------------------------------------------
            */

            if (!$session) {

                abort(

                    404,

                    'Session Expired.'

                );
            }

            /*
            |--------------------------------------------------------------------------
            | DATA
            |--------------------------------------------------------------------------
            */

            $data =
                $session['data'] ?? [];

            $files =
                $session['files'] ?? [];

            /*
            |--------------------------------------------------------------------------
            | STORE DATA
            |--------------------------------------------------------------------------
            */

            $storeData = [

                'user_id' =>
                    auth()->id(),

                'application_no' =>

                    'PAN'

                    . date('YmdHis')

                    . rand(1000,9999),

                'pan_type' =>
                    'New PAN',

                'first_name' =>
                    $data['first_name'] ?? null,

                'middle_name' =>
                    $data['middle_name'] ?? null,

                'last_name' =>
                    $data['last_name'] ?? null,

                'gender' =>
                    $data['gender'] ?? null,

                'father_first_name' =>
                    $data['father_first_name'] ?? null,

                'father_middle_name' =>
                    $data['father_middle_name'] ?? null,

                'father_last_name' =>
                    $data['father_last_name'] ?? null,

                'mother_first_name' =>
                    $data['mother_first_name'] ?? null,

                'mother_middle_name' =>
                    $data['mother_middle_name'] ?? null,

                'mother_last_name' =>
                    $data['mother_last_name'] ?? null,

                'pan_print_name' =>
                    $data['pan_print_name'] ?? null,

                'mobile_no' =>
                    $data['mobile_no'] ?? null,

                'email' =>
                    $data['email'] ?? null,

                'house_no' =>
                    $data['house_no'] ?? null,

                'village' =>
                    $data['village'] ?? null,

                'post_office' =>
                    $data['post_office'] ?? null,

                'area' =>
                    $data['area'] ?? null,

                'state' =>
                    $data['state'] ?? null,

                'district' =>
                    $data['district'] ?? null,

                'pincode' =>
                    $data['pincode'] ?? null,

                'identity_proof' =>
                    $data['identity_proof'] ?? null,

                'address_proof' =>
                    $data['address_proof'] ?? null,

                'dob_proof' =>
                    $data['dob_proof'] ?? null,

                'dob' =>
                    $data['dob'] ?? null,

                'confirm_dob' =>
                    $data['confirm_dob'] ?? null,

                'aadhaar_no' =>
                    $data['aadhaar_no'] ?? null,

                'aadhaar_name' =>
                    $data['aadhaar_name'] ?? null,

                'signature_type' =>
                    $data['signature_type'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | FILES
                |--------------------------------------------------------------------------
                */

                'photo' =>
                    $files['photo'] ?? null,

                'signature' =>
                    $files['signature'] ?? null,

                'aadhaar_card' =>
                    $files['aadhaar_card'] ?? null,

                'dob_proof_file' =>
                    $files['dob_proof_file'] ?? null,

                'supporting_document' =>
                    $files['supporting_document'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                'amount' =>
                    107,

                'payment_status' =>
                    'Pending',

                'status' =>
                    'Pending',

                'wallet_deducted' =>
                    false,

                'wallet_deducted_at' =>
                    null,

                /*
                |--------------------------------------------------------------------------
                | META
                |--------------------------------------------------------------------------
                */

                'ip_address' =>
                    request()->ip(),

                'browser' =>
                    request()->userAgent(),

                'created_at' =>
                    now(),

                'updated_at' =>
                    now()

            ];

            /*
            |--------------------------------------------------------------------------
            | CREATE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application =

                $this->repository
                    ->create($storeData);

            /*
            |--------------------------------------------------------------------------
            | CLEAR SESSION
            |--------------------------------------------------------------------------
            */

            session()->forget(
                'pan_application'
            );

            request()->session()->save();

            /*
            |--------------------------------------------------------------------------
            | RETURN
            |--------------------------------------------------------------------------
            */

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
            ->history($userId);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id,
        int $userId
    ): PanApplication {

        return $this->repository
            ->findByUser(
                $id,
                $userId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        $request,
        int $id,
        int $userId
    ): bool {

        $application =

            $this->find(
                $id,
                $userId
            );

        return $this->repository
            ->update(

                $application,

                $request->validated()

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

        /*
        |--------------------------------------------------------------------------
        | DELETE FILES
        |--------------------------------------------------------------------------
        */

        $files = [

            $application->photo,

            $application->signature,

            $application->aadhaar_card,

            $application->dob_proof_file,

            $application->supporting_document

        ];

        foreach ($files as $file) {

            delete_uploaded_file($file);
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE RECORD
        |--------------------------------------------------------------------------
        */

        return $this->repository
            ->delete($application);
    }
}