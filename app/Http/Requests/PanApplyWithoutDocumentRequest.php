<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PanApplyWithoutDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('dob')) {

            try {

                $this->merge([
                    'dob' => Carbon::createFromFormat(
                        'd/m/Y',
                        trim($this->dob)
                    )->format('Y-m-d')
                ]);

            } catch (\Exception $e) {
                //
            }
        }

        if ($this->filled('confirm_dob')) {

            try {

                $this->merge([
                    'confirm_dob' => Carbon::createFromFormat(
                        'd/m/Y',
                        trim($this->confirm_dob)
                    )->format('Y-m-d')
                ]);

            } catch (\Exception $e) {
                //
            }
        }

        if ($this->filled('old_pan_number')) {

            $this->merge([
                'old_pan_number' => strtoupper(
                    trim($this->old_pan_number)
                )
            ]);
        }
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | PERSONAL
            |--------------------------------------------------------------------------
            */

            'first_name' => 'nullable|string|max:100',

            'middle_name' => 'nullable|string|max:100',

            'last_name' => 'required|string|max:100',

            'old_pan_number' =>
                'required|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',

            'gender' =>
                'required|in:Male,Female,Transgender',

            /*
            |--------------------------------------------------------------------------
            | FATHER
            |--------------------------------------------------------------------------
            */

            'father_first_name' =>
                'nullable|string|max:100',

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
                'nullable|string|max:100',

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

            'dob' => [
                'required',
                'date',
            ],

            'confirm_dob' => [
                'required',
                'date',
                'same:dob',
            ],

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
                    empty($this->existing_files['photo'])
                ),

                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],

            'signature' => [

                Rule::requiredIf(
                    empty($this->existing_files['signature'])
                ),

                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],

            'aadhaar_card' => [

                Rule::requiredIf(
                    empty($this->existing_files['aadhaar_card'])
                ),

                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'dob_proof_file' => [

                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'supporting_document' => [

                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'old_pan_number.regex' =>
                'Please enter a valid PAN Number.',

            'old_pan_number.size' =>
                'PAN Number must be 10 characters.',

            'confirm_dob.same' =>
                'Date of Birth and Re-enter DOB must match.',

            'dob.date' =>
                'Please enter a valid Date of Birth.',

            'photo.max' =>
                'Applicant Photo must not exceed 5 MB.',

            'signature.max' =>
                'Signature must not exceed 5 MB.',

            'aadhaar_card.max' =>
                'Aadhaar Card must not exceed 5 MB.',

            'dob_proof_file.max' =>
                'DOB Proof must not exceed 5 MB.',

            'supporting_document.max' =>
                'Supporting Document must not exceed 5 MB.',

            'photo.mimes' =>
                'Photo must be JPG, JPEG or PNG.',

            'signature.mimes' =>
                'Signature must be JPG, JPEG or PNG.',

            'aadhaar_card.mimes' =>
                'Aadhaar Card must be JPG, PNG or PDF.',

            'dob_proof_file.mimes' =>
                'DOB Proof must be JPG, PNG or PDF.',

            'supporting_document.mimes' =>
                'Supporting Document must be JPG, PNG or PDF.',
        ];
    }
}