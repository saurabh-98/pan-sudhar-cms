<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCscServiceRequest extends FormRequest
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
            | PM KISAN
            |--------------------------------------------------------------------------
            */

            case 'pm-kisan-registration':

                $rules += [

                    'farmer_name' => 'required|string|max:255',

                    'mobile' => 'required|digits:10',

                    'aadhaar_number' => 'required|digits:12',

                    'bank_account' => 'nullable|string|max:50',

                    'passbook' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | AYUSHMAN CARD
            |--------------------------------------------------------------------------
            */

            case 'ayushman-card':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'mobile' => 'required|digits:10',

                    'aadhaar_number' => 'required|digits:12',

                    'family_id' => 'nullable|string|max:100',

                    'ration_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | INCOME CERTIFICATE
            |--------------------------------------------------------------------------
            */

            case 'income-certificate':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'occupation' => 'required|string|max:255',

                    'annual_income' => 'required|numeric',

                    'aadhaar_number' => 'required|digits:12',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | RESIDENCE CERTIFICATE
            |--------------------------------------------------------------------------
            */

            case 'residence-certificate':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'address' => 'required|string|max:1000',

                    'aadhaar_number' => 'required|digits:12',

                    'residence_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | CASTE CERTIFICATE
            |--------------------------------------------------------------------------
            */

            case 'caste-certificate':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'aadhaar_number' => 'required|digits:12',

                    'category' => [

                        'required',

                        Rule::in([
                            'SC',
                            'ST',
                            'OBC',
                            'EBC',
                        ]),

                    ],

                    'caste_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | BIRTH CERTIFICATE
            |--------------------------------------------------------------------------
            */

            case 'birth-certificate':

                $rules += [

                    'child_name' => 'required|string|max:255',

                    'dob' => 'required|date',

                    'father_name' => 'required|string|max:255',

                    'mother_name' => 'required|string|max:255',

                    'hospital_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | DEATH CERTIFICATE
            |--------------------------------------------------------------------------
            */

            case 'death-certificate':

                $rules += [

                    'deceased_name' => 'required|string|max:255',

                    'death_date' => 'required|date',

                    'death_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | LABOUR CARD
            |--------------------------------------------------------------------------
            */

            case 'labour-card':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'occupation' => 'required|string|max:255',

                    'experience_year' => 'nullable|numeric',

                    'aadhaar_number' => 'required|digits:12',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | E-SHRAM CARD
            |--------------------------------------------------------------------------
            */

            case 'e-shram-card':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'occupation' => 'required|string|max:255',

                    'mobile' => 'required|digits:10',

                    'aadhaar_number' => 'required|digits:12',

                    'bank_account' => 'nullable|string|max:50',

                ];

            break;

            /*
            |--------------------------------------------------------------------------
            | RATION CARD
            |--------------------------------------------------------------------------
            */

            case 'ration-card':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'family_members' => 'required|numeric',

                    'family_details' => 'nullable|string|max:2000',

                    'aadhaar_number' => 'required|digits:12',

                ];

            break;

            default:

                $rules['service_slug'][] = Rule::in([

                    'pm-kisan-registration',

                    'ayushman-card',

                    'income-certificate',

                    'residence-certificate',

                    'caste-certificate',

                    'birth-certificate',

                    'death-certificate',

                    'labour-card',

                    'e-shram-card',

                    'ration-card',

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