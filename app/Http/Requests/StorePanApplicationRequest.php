<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePanApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | PERSONAL
            |--------------------------------------------------------------------------
            */

            'first_name' => 'required|string|max:100',

            'middle_name' => 'nullable|string|max:100',

            'last_name' => 'required|string|max:100',

            'gender' =>
                'required|in:Male,Female,Transgender',

            /*
            |--------------------------------------------------------------------------
            | FATHER
            |--------------------------------------------------------------------------
            */

            'father_first_name' =>
                'required|string|max:100',

            'father_middle_name' =>
                'nullable|string|max:100',

            'father_last_name' =>
                'required|string|max:100',

            /*
            |--------------------------------------------------------------------------
            | MOTHER
            |--------------------------------------------------------------------------
            */

            'mother_first_name' =>
                'required|string|max:100',

            'mother_middle_name' =>
                'nullable|string|max:100',

            'mother_last_name' =>
                'required|string|max:100',

            /*
            |--------------------------------------------------------------------------
            | PAN
            |--------------------------------------------------------------------------
            */

            'pan_print_name' =>
                'required|in:Father,Mother',

            /*
            |--------------------------------------------------------------------------
            | CONTACT
            |--------------------------------------------------------------------------
            */

            'mobile_no' =>
                'required|digits:10',

            'email' =>
                'required|email',

            /*
            |--------------------------------------------------------------------------
            | ADDRESS
            |--------------------------------------------------------------------------
            */

            'house_no' =>
                'required|string|max:255',

            'village' =>
                'required|string|max:255',

            'post_office' =>
                'required|string|max:255',

            'area' =>
                'required|string|max:255',

            'state' =>
                'required|exists:states,id',

            'district' =>
                'required|exists:districts,id',

            'pincode' =>
                'required|digits:6',

            /*
            |--------------------------------------------------------------------------
            | PROOFS
            |--------------------------------------------------------------------------
            */

            'identity_proof' =>
                'required',

            'address_proof' =>
                'required',

            'dob_proof' =>
                'required',

            /*
            |--------------------------------------------------------------------------
            | DOB
            |--------------------------------------------------------------------------
            */

            'dob' =>
                'required|date',

            'confirm_dob' =>
                'required|same:dob',

            /*
            |--------------------------------------------------------------------------
            | AADHAAR
            |--------------------------------------------------------------------------
            */

            'aadhaar_no' =>
                'required|digits:12',

            'aadhaar_name' =>
                'required|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | SIGNATURE
            |--------------------------------------------------------------------------
            */

            'signature_type' =>
                'required|in:Signature,Thumb Impression',

            /*
            |--------------------------------------------------------------------------
            | FILES
            |--------------------------------------------------------------------------
            */

            'photo' => [

                Rule::requiredIf(

                    empty(
                        $this->existing_files['photo']
                    )

                ),

                'nullable',

                'image',

                'mimes:jpg,jpeg,png',

                'max:2048'

            ],

            'signature' => [

                Rule::requiredIf(

                    empty(
                        $this->existing_files['signature']
                    )

                ),

                'nullable',

                'image',

                'mimes:jpg,jpeg,png',

                'max:2048'

            ],

            'aadhaar_card' => [

                Rule::requiredIf(

                    empty(
                        $this->existing_files['aadhaar_card']
                    )

                ),

                'nullable',

                'mimes:jpg,jpeg,png,pdf',

                'max:4096'

            ],

            'identity_proof_file' => [

                Rule::requiredIf(

                    empty(
                        $this->existing_files['identity_proof_file']
                    )

                ),

                'nullable',

                'mimes:jpg,jpeg,png,pdf',

                'max:4096'

            ],

            'address_proof_file' => [

                Rule::requiredIf(

                    empty(
                        $this->existing_files['address_proof_file']
                    )

                ),

                'nullable',

                'mimes:jpg,jpeg,png,pdf',

                'max:4096'

            ],

            'dob_proof_file' => [

                Rule::requiredIf(

                    empty(
                        $this->existing_files['dob_proof_file']
                    )

                ),

                'nullable',

                'mimes:jpg,jpeg,png,pdf',

                'max:4096'

            ],

            'supporting_document' => [

                'nullable',

                'mimes:jpg,jpeg,png,pdf',

                'max:4096'

            ],

        ];
    }
}