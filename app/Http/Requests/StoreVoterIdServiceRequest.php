<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVoterIdServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $service = $this->input('service_slug');

        $rules = [

            'service_name' => [
                'required',
                'string',
                'max:255',
            ],

            'service_slug' => [
                'required',
                'string',
                'max:255',
            ],

        ];

        switch ($service) {

            /*
            |--------------------------------------------------------------------------
            | NEW VOTER ID APPLY
            |--------------------------------------------------------------------------
            */

            case 'new-voter-id':

                $rules += [

                    'applicant_name' => 'required|string|max:255',

                    'father_name' => 'required|string|max:255',

                    'mobile' => 'required|digits:10',

                    'aadhaar_number' => 'nullable|digits:12',

                    'dob' => 'required|date',

                    'gender' => [
                        'required',
                        Rule::in([
                            'Male',
                            'Female',
                            'Transgender',
                        ]),
                    ],

                    'address' => 'required|string|max:1000',

                    'district' => 'required|string|max:255',

                    'state' => 'required|string|max:255',

                    'pincode' => 'required|digits:6',

                    'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

                    'age_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

                    'address_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | VOTER ID CORRECTION
            |--------------------------------------------------------------------------
            */

            case 'voter-id-correction':

                $rules += [

                    'epic_number' => 'required|string|max:50',

                    'applicant_name' => 'required|string|max:255',

                    'correction_details' => 'required|string|max:2000',

                    'supporting_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | MOBILE UPDATE
            |--------------------------------------------------------------------------
            */

            case 'voter-id-mobile-update':

                $rules += [

                    'epic_number' => 'required|string|max:50',

                    'new_mobile' => 'required|digits:10',

                    'aadhaar_number' => 'nullable|digits:12',

                    'identity_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | ADDRESS UPDATE
            |--------------------------------------------------------------------------
            */

            case 'voter-id-address-update':

                $rules += [

                    'epic_number' => 'required|string|max:50',

                    'new_address' => 'required|string|max:1000',

                    'district' => 'required|string|max:255',

                    'state' => 'required|string|max:255',

                    'pincode' => 'required|digits:6',

                    'address_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | DOB UPDATE
            |--------------------------------------------------------------------------
            */

            case 'voter-id-dob-update':

                $rules += [

                    'epic_number' => 'required|string|max:50',

                    'current_dob' => 'nullable|date',

                    'new_dob' => 'required|date',

                    'dob_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            default:

                $rules['service_slug'][] = Rule::in([

                    'new-voter-id',

                    'voter-id-correction',

                    'voter-id-mobile-update',

                    'voter-id-address-update',

                    'voter-id-dob-update',

                ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [

            '*.required' =>
                'This field is required.',

            '*.digits' =>
                'Invalid number format.',

            '*.email' =>
                'Please enter a valid email address.',

            '*.mimes' =>
                'Only JPG, JPEG, PNG and PDF files are allowed.',

            '*.max' =>
                'The uploaded file size exceeds the allowed limit.',
        ];
    }
}