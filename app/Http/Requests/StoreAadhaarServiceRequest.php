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
                    'old_mobile' => 'required|digits:10',
                    'aadhaar_number' => 'required|digits:12',
                    'new_mobile' => 'required|digits:10',
                    'aadhaar_acknowledgement' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',                ];

            break;

            case 'name-correction':

                $rules += [

                    'customer_name' => 'required|string|max:255',
                    'new_name' => 'required|string|max:255',
                    'mobile' => 'required|digits:10',
                    'alternate_mobile' => 'nullable|digits:10',
                    'aadhaar_number' => 'required|digits:12',
                    'aadhaar_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'supportive_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];

            break;

            case 'dob-correction':

                $rules += [

                   'customer_name' => 'required|string|max:255',
                    'old_dob' => 'nullable|date',
                    'new_dob' => 'required|date',
                    'mobile' => 'required|digits:10',
                    'alternate_mobile' => 'nullable|digits:10',
                    'aadhaar_number' => 'required|digits:12',
                    'aadhaar_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'dob_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'supportive_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];

            break;

            case 'address-update':

                $rules += [

                   'customer_name' => 'required|string|max:255',
                    'mobile' => 'required|digits:10',
                    'new_address' => 'required|string|max:1000',
                    'aadhaar_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'address_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];

            break;

            case  'father-husband-name-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'father_name' => 'required|string|max:255',

                    'aadhaar_number' => 'required|digits:12',
                ];

            break;


            case 'gender-update':

                $rules += [

                   'customer_name' => 'required|string|max:255',
                    'gender' => ['required', Rule::in(['Male','Female','Transgender'])],
                    'mobile' => 'required|digits:10',
                    'alternate_mobile' => 'nullable|digits:10',
                    'aadhaar_number' => 'required|digits:12',
                    'aadhaar_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'supportive_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];

            break;

            case 'email-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'mobile' => 'required|digits:10',
                    'alternate_mobile' => 'nullable|digits:10',
                    'aadhaar_number' => 'required|digits:12',
                    'aadhaar_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'supportive_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];

            break;

            case 'biometric-update':

                $rules += [

                    'customer_name' => 'required|string|max:255',
                    'biometric_type' => ['required', Rule::in(['Fingerprint','Iris','Face'])],
                    'mobile' => 'required|digits:10',
                    'alternate_mobile' => 'nullable|digits:10',
                    'aadhaar_number' => 'required|digits:12',
                    'aadhaar_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'supportive_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
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
                    'alternate_mobile' => 'nullable|digits:10',
                    'delivery_address' => 'required|string|max:1000',
                    'aadhaar_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'supportive_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
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
                    'father-husband-name-update',
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
            'required' => ':attribute is required.',
            'digits' => ':attribute must be valid.',
            'email' => 'Please enter a valid email address.',
            'mimes' => 'Only JPG, JPEG, PNG and PDF files are allowed.',
            'max' => 'The uploaded file exceeds the maximum allowed size.',
        ];
    }
}