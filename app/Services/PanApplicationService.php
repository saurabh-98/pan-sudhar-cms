<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
    | PREVIEW SESSION
    |--------------------------------------------------------------------------
    */

    public function preview(
        PanApplicationDTO $dto
    ): array {

        /*
        |--------------------------------------------------------------------------
        | EXISTING SESSION FILES
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

                ? $dto->photo->store(
                    'temp/pan/photo',
                    'public'
                )

                : ($existingFiles['photo'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | SIGNATURE
        |--------------------------------------------------------------------------
        */

        $signaturePath =

            $dto->signature

                ? $dto->signature->store(
                    'temp/pan/signature',
                    'public'
                )

                : ($existingFiles['signature'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | AADHAAR CARD
        |--------------------------------------------------------------------------
        */

        $aadhaarCardPath =

            $dto->aadhaar_card

                ? $dto->aadhaar_card->store(
                    'temp/pan/aadhaar',
                    'public'
                )

                : ($existingFiles['aadhaar_card'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | IDENTITY PROOF
        |--------------------------------------------------------------------------
        */

        $identityProofFilePath =

            $dto->identity_proof_file

                ? $dto->identity_proof_file->store(
                    'temp/pan/identity-proof',
                    'public'
                )

                : ($existingFiles['identity_proof_file'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | ADDRESS PROOF
        |--------------------------------------------------------------------------
        */

        $addressProofFilePath =

            $dto->address_proof_file

                ? $dto->address_proof_file->store(
                    'temp/pan/address-proof',
                    'public'
                )

                : ($existingFiles['address_proof_file'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | DOB PROOF
        |--------------------------------------------------------------------------
        */

        $dobProofFilePath =

            $dto->dob_proof_file

                ? $dto->dob_proof_file->store(
                    'temp/pan/dob-proof',
                    'public'
                )

                : ($existingFiles['dob_proof_file'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | SUPPORTING DOCUMENT
        |--------------------------------------------------------------------------
        */

        $supportingDocumentPath =

            $dto->supporting_document

                ? $dto->supporting_document->store(
                    'temp/pan/document',
                    'public'
                )

                : ($existingFiles['supporting_document'] ?? null);

        /*
        |--------------------------------------------------------------------------
        | SESSION STORE
        |--------------------------------------------------------------------------
        */

        session([

            'pan_application' => [

                'data' =>

                    $dto->previewArray(),

                'files' => [

                    'photo' =>
                        $photoPath,

                    'signature' =>
                        $signaturePath,

                    'aadhaar_card' =>
                        $aadhaarCardPath,

                    'identity_proof_file' =>
                        $identityProofFilePath,

                    'address_proof_file' =>
                        $addressProofFilePath,

                    'dob_proof_file' =>
                        $dobProofFilePath,

                    'supporting_document' =>
                        $supportingDocumentPath

                ]

            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | RETURN PREVIEW DATA
        |--------------------------------------------------------------------------
        */

        return [

            'data' => session(
                'pan_application.data'
            ),

            'files' => session(
                'pan_application.files'
            )

        ];
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
            | SESSION DATA
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
            | MOVE FILES
            |--------------------------------------------------------------------------
            */

            foreach ($files as $key => $file) {

                if ($file) {

                    $newPath = str_replace(

                        'temp/pan',

                        'pan',

                        $file

                    );

                    if (
                        Storage::disk('public')
                            ->exists($file)
                    ) {

                        Storage::disk('public')

                            ->move(
                                $file,
                                $newPath
                            );
                    }

                    $files[$key] =
                        $newPath;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | STORE DATA
            |--------------------------------------------------------------------------
            */

            $storeData = [

                /*
                |--------------------------------------------------------------------------
                | USER
                |--------------------------------------------------------------------------
                */

                'user_id' =>
                    auth()->id(),

                /*
                |--------------------------------------------------------------------------
                | APPLICATION
                |--------------------------------------------------------------------------
                */

                'application_no' =>

                    'PAN'.

                    date('YmdHis').

                    rand(1000,9999),

                'pan_type' =>
                    'New PAN',

                /*
                |--------------------------------------------------------------------------
                | PERSONAL
                |--------------------------------------------------------------------------
                */

                'first_name' =>
                    $data['first_name'] ?? null,

                'middle_name' =>
                    $data['middle_name'] ?? null,

                'last_name' =>
                    $data['last_name'] ?? null,

                'gender' =>
                    $data['gender'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | FATHER
                |--------------------------------------------------------------------------
                */

                'father_first_name' =>
                    $data['father_first_name'] ?? null,

                'father_middle_name' =>
                    $data['father_middle_name'] ?? null,

                'father_last_name' =>
                    $data['father_last_name'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | MOTHER
                |--------------------------------------------------------------------------
                */

                'mother_first_name' =>
                    $data['mother_first_name'] ?? null,

                'mother_middle_name' =>
                    $data['mother_middle_name'] ?? null,

                'mother_last_name' =>
                    $data['mother_last_name'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | PAN
                |--------------------------------------------------------------------------
                */

                'pan_print_name' =>
                    $data['pan_print_name'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | CONTACT
                |--------------------------------------------------------------------------
                */

                'mobile_no' =>
                    $data['mobile_no'] ?? null,

                'email' =>
                    $data['email'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | ADDRESS
                |--------------------------------------------------------------------------
                */

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

                /*
                |--------------------------------------------------------------------------
                | PROOFS
                |--------------------------------------------------------------------------
                */

                'identity_proof' =>
                    $data['identity_proof'] ?? null,

                'address_proof' =>
                    $data['address_proof'] ?? null,

                'dob_proof' =>
                    $data['dob_proof'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | DOB
                |--------------------------------------------------------------------------
                */

                'dob' =>
                    $data['dob'] ?? null,

                'confirm_dob' =>
                    $data['confirm_dob'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | AADHAAR
                |--------------------------------------------------------------------------
                */

                'aadhaar_no' =>
                    $data['aadhaar_no'] ?? null,

                'aadhaar_name' =>
                    $data['aadhaar_name'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | SIGNATURE
                |--------------------------------------------------------------------------
                */

                'signature_type' =>
                    $data['signature_type'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | DOCUMENTS
                |--------------------------------------------------------------------------
                */

                'photo' =>
                    $files['photo'] ?? null,

                'signature' =>
                    $files['signature'] ?? null,

                'aadhaar_card' =>
                    $files['aadhaar_card'] ?? null,

                'identity_proof_file' =>
                    $files['identity_proof_file'] ?? null,

                'address_proof_file' =>
                    $files['address_proof_file'] ?? null,

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

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                'status' =>
                    'Pending',

                /*
                |--------------------------------------------------------------------------
                | WALLET
                |--------------------------------------------------------------------------
                */

                'wallet_deducted' =>
                    false,

                'wallet_deducted_at' =>
                    null,

                /*
                |--------------------------------------------------------------------------
                | SECURITY
                |--------------------------------------------------------------------------
                */

                'ip_address' =>
                    request()->ip(),

                'browser' =>
                    request()->userAgent(),

                /*
                |--------------------------------------------------------------------------
                | TIMESTAMPS
                |--------------------------------------------------------------------------
                */

                'created_at' =>
                    now(),

                'updated_at' =>
                    now()

            ];

            /*
            |--------------------------------------------------------------------------
            | STORE DATABASE
            |--------------------------------------------------------------------------
            */

            $application = $this->repository
                ->create($storeData);

            /*
            |--------------------------------------------------------------------------
            | CLEAR SESSION
            |--------------------------------------------------------------------------
            */

            session()->forget(
                'pan_application'
            );

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

        $files = [

            $application->photo,

            $application->signature,

            $application->aadhaar_card,

            $application->identity_proof_file,

            $application->address_proof_file,

            $application->dob_proof_file,

            $application->supporting_document

        ];

        foreach ($files as $file) {

            if ($file) {

                Storage::disk('public')
                    ->delete($file);
            }
        }

        return $this->repository
            ->delete($application);
    }
}