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
        PanApplicationDTO $dto
    ): array {


        /*
        |--------------------------------------------------------------------------
        | OLD SESSION FILES
        |--------------------------------------------------------------------------
        */

        $oldPreview =
            get_pan_session();



        $existingFiles =
            $oldPreview['files'] ?? [];



        /*
        |--------------------------------------------------------------------------
        | UPLOAD / KEEP OLD FILES
        |--------------------------------------------------------------------------
        */

        $photoPath =
            $dto->photo

                ? $this->storeFile(
                    $dto->photo,
                    'pan/photo'
                )

                : ($existingFiles['photo'] ?? null);



        $signaturePath =
            $dto->signature

                ? $this->storeFile(
                    $dto->signature,
                    'pan/signature'
                )

                : ($existingFiles['signature'] ?? null);



        $aadhaarCardPath =
            $dto->aadhaar_card

                ? $this->storeFile(
                    $dto->aadhaar_card,
                    'pan/aadhaar'
                )

                : ($existingFiles['aadhaar_card'] ?? null);



        $dobProofFilePath =
            $dto->dob_proof_file

                ? $this->storeFile(
                    $dto->dob_proof_file,
                    'pan/dob-proof'
                )

                : ($existingFiles['dob_proof_file'] ?? null);



        $supportingDocumentPath =
            $dto->supporting_document

                ? $this->storeFile(
                    $dto->supporting_document,
                    'pan/document'
                )

                : ($existingFiles['supporting_document'] ?? null);




        /*
        |--------------------------------------------------------------------------
        | PREVIEW DATA
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
        | SAVE SESSION USING HELPER
        |--------------------------------------------------------------------------
        */

        save_pan_session(
            $previewData
        );



        return $previewData;

    }





    /*
    |--------------------------------------------------------------------------
    | FINAL STORE
    |--------------------------------------------------------------------------
    */

    public function storeFromSession(): PanApplication
    {

        return DB::transaction(function () {


            /*
            |--------------------------------------------------------------------------
            | GET SESSION
            |--------------------------------------------------------------------------
            */

            $session =
                get_pan_session();



            if (!$session) {


                abort(

                    404,

                    'Session Expired.'

                );
            }



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
                    150,


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



            /*
            |--------------------------------------------------------------------------
            | CREATE
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

            clear_pan_session();




            return $application;

        });

    }





    public function history(
        int $userId
    )
    {

        return $this->repository

            ->history($userId);

    }




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



        return $this->repository

            ->delete(

                $application

            );

    }

}