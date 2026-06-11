<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAadhaarServiceRequest extends FormRequest
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

            case 'mobile-number-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'mobile' => 'required|digits:10',

                    'aadhaar_number' => 'required|digits:12',

                    'aadhaar_front' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                    'aadhaar_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];

            break;

            case 'name-correction':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'new_name' => 'required|string|max:255',

                    'aadhaar_number' => 'required|digits:12',

                    'name_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];

            break;

            case 'dob-correction':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'old_dob' => 'nullable|date',

                    'new_dob' => 'required|date',

                    'aadhaar_number' => 'required|digits:12',

                    'dob_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];

            break;

            case 'address-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'aadhaar_number' => 'required|digits:12',

                    'new_address' => 'required|string|max:1000',

                    'address_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];

            break;

            case 'father-name-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'father_name' => 'required|string|max:255',

                    'aadhaar_number' => 'required|digits:12',
                ];

            break;

            case 'husband-name-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'husband_name' => 'required|string|max:255',

                    'aadhaar_number' => 'required|digits:12',
                ];

            break;

            case 'gender-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'aadhaar_number' => 'required|digits:12',

                    'gender' => [
                        'required',
                        Rule::in([
                            'Male',
                            'Female',
                            'Transgender'
                        ]),
                    ],
                ];

            break;

            case 'email-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'email' => 'required|email|max:255',

                    'aadhaar_number' => 'required|digits:12',
                ];

            break;

            case 'biometric-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'mobile' => 'required|digits:10',

                    'aadhaar_number' => 'required|digits:12',
                ];

            break;

            case 'child-aadhaar-enrollment':

                $rules += [

                    'child_name' => 'required|string|max:255',

                    'child_dob' => 'required|date',

                    'father_name' => 'required|string|max:255',

                    'mother_name' => 'required|string|max:255',
                ];

            break;

            case 'new-aadhaar-apply':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'father_name' => 'required|string|max:255',

                    'mobile' => 'required|digits:10',

                    'address' => 'required|string|max:1000',
                ];

            break;

            case 'aadhaar-pvc-card':

                $rules += [

                    'aadhaar_number' => 'required|digits:12',

                    'mobile' => 'required|digits:10',
                ];

            break;

            case 'aadhaar-download':

                $rules += [

                    'aadhaar_number' => 'required|digits:12',
                ];

            break;

            case 'aadhaar-status-check':

                $rules += [

                    'enrollment_number' => 'required|string|max:50',
                ];

            break;

            case 'aadhaar-verification':

                $rules += [

                    'aadhaar_number' => 'required|digits:12',
                ];

            break;

            default:

                $rules['service_slug'][] = Rule::in([
                    'mobile-number-update',
                    'name-correction',
                    'dob-correction',
                    'address-update',
                    'father-name-update',
                    'husband-name-update',
                    'gender-update',
                    'email-update',
                    'biometric-update',
                    'child-aadhaar-enrollment',
                    'new-aadhaar-apply',
                    'aadhaar-pvc-card',
                    'aadhaar-download',
                    'aadhaar-status-check',
                    'aadhaar-verification',
                ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [

            '*.required' => 'This field is required.',

            '*.digits' => 'Invalid number format.',

            '*.email' => 'Please enter a valid email address.',

            '*.mimes' => 'Only JPG, JPEG, PNG and PDF files are allowed.',

            '*.max' => 'The uploaded file size exceeds the allowed limit.',
        ];
    }
}