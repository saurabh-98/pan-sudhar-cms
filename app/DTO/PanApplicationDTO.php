<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;
use App\Http\Requests\StorePanApplicationRequest;

class PanApplicationDTO
{
    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(

        /*
        |--------------------------------------------------------------------------
        | PERSONAL
        |--------------------------------------------------------------------------
        */

        public readonly ?string $first_name,

        public readonly ?string $middle_name,

        public readonly string $last_name,

        public readonly string $gender,

        /*
        |--------------------------------------------------------------------------
        | FATHER
        |--------------------------------------------------------------------------
        */

        public readonly ?string $father_first_name,

        public readonly ?string $father_middle_name,

        public readonly string $father_last_name,

        /*
        |--------------------------------------------------------------------------
        | MOTHER
        |--------------------------------------------------------------------------
        */

        public readonly ?string $mother_first_name,

        public readonly ?string $mother_middle_name,

        public readonly string $mother_last_name,

        /*
        |--------------------------------------------------------------------------
        | PAN
        |--------------------------------------------------------------------------
        */

        public readonly string $pan_print_name,

        /*
        |--------------------------------------------------------------------------
        | CONTACT
        |--------------------------------------------------------------------------
        */

        public readonly string $mobile_no,

        public readonly string $email,

        /*
        |--------------------------------------------------------------------------
        | ADDRESS
        |--------------------------------------------------------------------------
        */

        public readonly string $house_no,

        public readonly string $village,

        public readonly string $post_office,

        public readonly string $area,

        public readonly string $state,

        public readonly string $district,

        public readonly string $pincode,

        /*
        |--------------------------------------------------------------------------
        | PROOFS
        |--------------------------------------------------------------------------
        */

        public readonly string $identity_proof,

        public readonly string $address_proof,

        public readonly string $dob_proof,

        /*
        |--------------------------------------------------------------------------
        | DOB
        |--------------------------------------------------------------------------
        */

        public readonly string $dob,

        public readonly string $confirm_dob,

        /*
        |--------------------------------------------------------------------------
        | AADHAAR
        |--------------------------------------------------------------------------
        */

        public readonly string $aadhaar_no,

        public readonly string $aadhaar_name,

        /*
        |--------------------------------------------------------------------------
        | SIGNATURE
        |--------------------------------------------------------------------------
        */

        public readonly string $signature_type,

        /*
        |--------------------------------------------------------------------------
        | FILES
        |--------------------------------------------------------------------------
        */

        public readonly ?UploadedFile $photo = null,

        public readonly ?UploadedFile $signature = null,

        public readonly ?UploadedFile $aadhaar_card = null,

        public readonly ?UploadedFile $dob_proof_file = null,

        public readonly ?UploadedFile $supporting_document = null,

        /*
        |--------------------------------------------------------------------------
        | CHARGES
        |--------------------------------------------------------------------------
        */

        public readonly float $amount = 107.00

    ) {}

    /*
    |--------------------------------------------------------------------------
    | CREATE DTO FROM REQUEST
    |--------------------------------------------------------------------------
    */

    public static function fromRequest(

        StorePanApplicationRequest $request

    ): self {

        return new self(

            /*
            |--------------------------------------------------------------------------
            | PERSONAL
            |--------------------------------------------------------------------------
            */

            first_name:
                $request->middle_name
                    ? trim($request->first_name)
                    : null,

            middle_name:
                $request->middle_name
                    ? trim($request->middle_name)
                    : null,

            last_name:
                trim($request->last_name),

            gender:
                trim($request->gender),

            /*
            |--------------------------------------------------------------------------
            | FATHER
            |--------------------------------------------------------------------------
            */

            father_first_name:
                    $request->father_first_name
                        ? trim($request->father_first_name)
                        : null,
            father_middle_name:
                $request->father_middle_name
                    ? trim($request->father_middle_name)
                    : null,

            father_last_name:
                trim($request->father_last_name),

            /*
            |--------------------------------------------------------------------------
            | MOTHER
            |--------------------------------------------------------------------------
            */

            mother_first_name:
                    $request->mother_first_name
                    ? trim($request->mother_first_name)
                    : null,
               

            mother_middle_name:
                $request->mother_middle_name
                    ? trim($request->mother_middle_name)
                    : null,

            mother_last_name:
                trim($request->mother_last_name),

            /*
            |--------------------------------------------------------------------------
            | PAN
            |--------------------------------------------------------------------------
            */

            pan_print_name:
                trim($request->pan_print_name),

            /*
            |--------------------------------------------------------------------------
            | CONTACT
            |--------------------------------------------------------------------------
            */

            mobile_no:
                trim($request->mobile_no),

            email:
                trim($request->email),

            /*
            |--------------------------------------------------------------------------
            | ADDRESS
            |--------------------------------------------------------------------------
            */

            house_no:
                trim($request->house_no),

            village:
                trim($request->village),

            post_office:
                trim($request->post_office),

            area:
                trim($request->area),

            state:
                (string) $request->state,

            district:
                (string) $request->district,

            pincode:
                trim($request->pincode),

            /*
            |--------------------------------------------------------------------------
            | PROOFS
            |--------------------------------------------------------------------------
            */

            identity_proof:
                trim($request->identity_proof),

            address_proof:
                trim($request->address_proof),

            dob_proof:
                trim($request->dob_proof),

            /*
            |--------------------------------------------------------------------------
            | DOB
            |--------------------------------------------------------------------------
            */

            dob:
                trim($request->dob),

            confirm_dob:
                trim($request->confirm_dob),

            /*
            |--------------------------------------------------------------------------
            | AADHAAR
            |--------------------------------------------------------------------------
            */

            aadhaar_no:
                trim($request->aadhaar_no),

            aadhaar_name:
                trim($request->aadhaar_name),

            /*
            |--------------------------------------------------------------------------
            | SIGNATURE
            |--------------------------------------------------------------------------
            */

            signature_type:
                trim($request->signature_type),

            /*
            |--------------------------------------------------------------------------
            | FILES
            |--------------------------------------------------------------------------
            */

            photo:

                $request->hasFile('photo')

                    ? $request->file('photo')

                    : null,

            signature:

                $request->hasFile('signature')

                    ? $request->file('signature')

                    : null,

            aadhaar_card:

                $request->hasFile('aadhaar_card')

                    ? $request->file('aadhaar_card')

                    : null,



            dob_proof_file:

                $request->hasFile('dob_proof_file')

                    ? $request->file('dob_proof_file')

                    : null,

            supporting_document:

                $request->hasFile('supporting_document')

                    ? $request->file('supporting_document')

                    : null,

            /*
            |--------------------------------------------------------------------------
            | CHARGE
            |--------------------------------------------------------------------------
            */

            amount: 107.00

        );
    }

    /*
    |--------------------------------------------------------------------------
    | DATABASE ARRAY
    |--------------------------------------------------------------------------
    */

    public function toArray(

        string $photoPath,

        string $signaturePath,

        string $aadhaarCardPath,

        ?string $dobProofFilePath,

        ?string $supportingDocumentPath

    ): array {

        return [

            'user_id' =>
                auth()->id(),

            'application_no' =>

                'PAN'

                . now()->format('YmdHis')

                . rand(1000,9999),

            'pan_type' =>
                'New PAN',

            'first_name' =>
                $this->first_name,

            'middle_name' =>
                $this->middle_name,

            'last_name' =>
                $this->last_name,

            'dob' =>
                $this->dob,

            'gender' =>
                $this->gender,

            'father_first_name' =>
                $this->father_first_name,

            'father_middle_name' =>
                $this->father_middle_name,

            'father_last_name' =>
                $this->father_last_name,

            'mother_first_name' =>
                $this->mother_first_name,

            'mother_middle_name' =>
                $this->mother_middle_name,

            'mother_last_name' =>
                $this->mother_last_name,

            'pan_print_name' =>
                $this->pan_print_name,

            'mobile_no' =>
                $this->mobile_no,

            'email' =>
                $this->email,

            'house_no' =>
                $this->house_no,

            'village' =>
                $this->village,

            'post_office' =>
                $this->post_office,

            'area' =>
                $this->area,

            'state' =>
                $this->state,

            'district' =>
                $this->district,

            'pincode' =>
                $this->pincode,

            'identity_proof' =>
                $this->identity_proof,

            'address_proof' =>
                $this->address_proof,

            'dob_proof' =>
                $this->dob_proof,

            'aadhaar_no' =>
                $this->aadhaar_no,

            'aadhaar_name' =>
                $this->aadhaar_name,

            'signature_type' =>
                $this->signature_type,

            'photo' =>
                $photoPath,

            'signature' =>
                $signaturePath,

            'aadhaar_card' =>
                $aadhaarCardPath,


            'dob_proof_file' =>
                $dobProofFilePath,

            'supporting_document' =>
                $supportingDocumentPath,

            'amount' =>
                $this->amount,

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
                substr(

                    request()->userAgent(),

                    0,

                    1000

                ),

            'admin_remark' =>
                null,

            'created_at' =>
                now(),

            'updated_at' =>
                now()

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW ARRAY
    |--------------------------------------------------------------------------
    */

    public function previewArray(): array
    {
        return [

            'first_name' =>
                $this->first_name,

            'middle_name' =>
                $this->middle_name,

            'last_name' =>
                $this->last_name,

            'gender' =>
                $this->gender,

            'father_first_name' =>
                $this->father_first_name,

            'father_middle_name' =>
                $this->father_middle_name,

            'father_last_name' =>
                $this->father_last_name,

            'mother_first_name' =>
                $this->mother_first_name,

            'mother_middle_name' =>
                $this->mother_middle_name,

            'mother_last_name' =>
                $this->mother_last_name,

            'pan_print_name' =>
                $this->pan_print_name,

            'mobile_no' =>
                $this->mobile_no,

            'email' =>
                $this->email,

            'house_no' =>
                $this->house_no,

            'village' =>
                $this->village,

            'post_office' =>
                $this->post_office,

            'area' =>
                $this->area,

            'state' =>
                $this->state,

            'district' =>
                $this->district,

            'pincode' =>
                $this->pincode,

            'identity_proof' =>
                $this->identity_proof,

            'address_proof' =>
                $this->address_proof,

            'dob_proof' =>
                $this->dob_proof,

            'dob' =>
                $this->dob,

            'confirm_dob' =>
                $this->confirm_dob,

            'aadhaar_no' =>
                $this->aadhaar_no,

            'aadhaar_name' =>
                $this->aadhaar_name,

            'signature_type' =>
                $this->signature_type,

            'amount' =>
                $this->amount,

        ];
    }
}