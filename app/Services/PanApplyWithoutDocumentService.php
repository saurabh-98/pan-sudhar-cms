<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DTO\PanWithoutDocumentDTO;
use App\Models\PanWithoutDocument;
use App\Repositories\PanApplyWithoutDocumentRepository;

class PanApplyWithoutDocumentService
{
    public function __construct(
        protected PanApplyWithoutDocumentRepository $panCorrectionRepository
    ) {}


    /*
    |--------------------------------------------------------------------------
    | STORE FILE (CLOUDINARY)
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
        PanApplyWithoutDocumenDTO $panCorrectionDto
    ): array {


        /*
        |--------------------------------------------------------------------------
        | OLD SESSION FILES
        |--------------------------------------------------------------------------
        */

        $oldPreview =
            get_pan_apply_without_document_session();



        $existingFiles =
            $oldPreview['files'] ?? [];



        /*
        |--------------------------------------------------------------------------
        | UPLOAD / KEEP OLD FILES
        |--------------------------------------------------------------------------
        */

        $photoPath =
            $panCorrectionDto->photo

                ? $this->storeFile(
                    $panCorrectionDto->photo,
                    'pan-correction/photo'
                )

                : ($existingFiles['photo'] ?? null);



        $signaturePath =
            $panCorrectionDto->signature

                ? $this->storeFile(
                    $panCorrectionDto->signature,
                    'pan-correction/signature'
                )

                : ($existingFiles['signature'] ?? null);



        $aadhaarCardPath =
            $panCorrectionDto->aadhaar_card

                ? $this->storeFile(
                    $panCorrectionDto->aadhaar_card,
                    'pan-correction/aadhaar'
                )

                : ($existingFiles['aadhaar_card'] ?? null);



        $dobProofFilePath =
            $panCorrectionDto->dob_proof_file

                ? $this->storeFile(
                    $panCorrectionDto->dob_proof_file,
                    'pan-correction/dob-proof'
                )

                : ($existingFiles['dob_proof_file'] ?? null);



        $supportingDocumentPath =
            $panCorrectionDto->supporting_document

                ? $this->storeFile(
                    $panCorrectionDto->supporting_document,
                    'pan-correction/document'
                )

                : ($existingFiles['supporting_document'] ?? null);




        /*
        |--------------------------------------------------------------------------
        | PREVIEW DATA
        |--------------------------------------------------------------------------
        */

        $previewData = [

            'data' =>

                $panCorrectionDto->previewArray(),


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
        | SAVE SESSION USING HELPER
        |--------------------------------------------------------------------------
        */

        save_pan_apply_without_document_session(
            $previewData
        );



        return $previewData;

    }





    /*
    |--------------------------------------------------------------------------
    | FINAL STORE
    |--------------------------------------------------------------------------
    */

    public function storeFromSession(): PanWithoutDocument
    {
        return DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | GET SESSION
            |--------------------------------------------------------------------------
            */

            $session = get_pan_apply_without_document_session();

            if (!$session) {

                abort(
                    404,
                    'Session Expired.'
                );
            }

            $data = $session['data'] ?? [];

            $files = $session['files'] ?? [];

            /*
            |--------------------------------------------------------------------------
            | DOB ALREADY SAVED AS Y-m-d
            |--------------------------------------------------------------------------
            */

            unset(
                $data['confirm_dob']
            );

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
                    'WITHOUT DOCUMENT PAN',

                ...$data,

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
                    $data['pan_charge'] ?? 107,

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

            unset($storeData['pan_charge']);

            $application =

                $this->panCorrectionRepository
                    ->create($storeData);

            /*
            |--------------------------------------------------------------------------
            | CLEAR SESSION
            |--------------------------------------------------------------------------
            */

            clear_pan_apply_without_document_session();

            return $application;

        });
    }




    public function history(
        int $userId
    )
    {

        return $this->panCorrectionRepository

            ->history($userId);

    }




    public function find(
        int $id,
        int $userId
    ): PanWithoutDocument {


        return $this->panCorrectionRepository

            ->findByUser(

                $id,

                $userId

            );
    }





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


        return $this->panCorrectionRepository

            ->update(

                $application,

                $request->validated()

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



        foreach ([


            $application->photo,


            $application->signature,


            $application->aadhaar_card,


            $application->dob_proof_file,


            $application->supporting_document


        ] as $file) {



            delete_uploaded_file(

                $file

            );

        }



        return $this->panCorrectionRepository

            ->delete(

                $application

            );

    }

}