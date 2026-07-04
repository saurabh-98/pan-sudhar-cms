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

            case 'pm-kisan-registration':

                $rules += [
                    'customer_name'   => 'required|string|max:255',
                    'mobile'          => 'required|digits:10',
                    'aadhaar_number'  => 'nullable|digits:12',
                    'khatuni'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;

            case 'ayushman-card':

                $rules += [
                    'customer_name'   => 'required|string|max:255',
                    'mobile'          => 'required|digits:10',
                    'aadhaar_number'  => 'nullable|digits:12',
                    'photo'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'document'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'family_id'       => 'nullable|string|max:255',
                    'ration_card'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;

            case 'income-certificate':

                $rules += [
                    'customer_name'   => 'required|string|max:255',
                    'mobile'          => 'required|digits:10',
                    'aadhaar_number'  => 'nullable|digits:12',
                    'mother_name'     => 'required|string|max:255',
                    'occupation'      => 'nullable|string|max:255',
                    'annual_income'   => 'nullable|numeric|min:0',
                    'photo'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'document'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;

            case 'domicile-niwas-certificate':

                $rules += [
                    'customer_name'    => 'required|string|max:255',
                    'mobile'           => 'required|digits:10',
                    'aadhaar_number'   => 'nullable|digits:12',
                    'mother_name'      => 'required|string|max:255',
                    'aadhaar_card'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'residence_proof'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'address'          => 'nullable|string|max:1000',
                ];
                break;

            case 'caste-certificate':

                $rules += [
                    'customer_name'   => 'required|string|max:255',
                    'mobile'          => 'required|digits:10',
                    'aadhaar_number'  => 'nullable|digits:12',
                    'mother_name'     => 'required|string|max:255',
                    'aadhaar_card'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'photo'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'category'        => 'nullable|in:SC,ST,OBC,EBC',
                    'caste_proof'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;

            case 'birth-certificate':

                $rules += [
                    'child_name'               => 'required|string|max:255',
                    'gender'                   => 'required|string|max:50',
                    'dob'                      => 'nullable|date',
                    'father_name'              => 'nullable|string|max:255',
                    'father_aadhaar_number'    => 'nullable|digits:12',
                    'mother_name'              => 'nullable|string|max:255',
                    'mother_aadhaar_number'    => 'nullable|digits:12',
                    'hospital_certificate'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;

            case 'death-certificate':

                $rules += [
                    'deceased_name' => 'required|string|max:255',
                    'death_date'    => 'nullable|date',
                    'death_proof'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;

            case 'labour-card':

                $rules += [
                    'customer_name'    => 'required|string|max:255',
                    'mobile'           => 'required|digits:10',
                    'aadhaar_number'   => 'nullable|digits:12',
                    'photo'            => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'document'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'occupation'       => 'nullable|string|max:255',
                    'experience_year'  => 'nullable|numeric|min:0',
                ];
                break;

            case 'e-shram-card':

                $rules += [
                    'customer_name'  => 'required|string|max:255',
                    'mobile'         => 'required|digits:10',
                    'aadhaar_number' => 'nullable|digits:12',
                    'occupation'     => 'nullable|string|max:255',
                    'bank_account'   => 'nullable|string|max:30',
                    'bank_passbook'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'photo'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'aadhaar_card'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;

            case 'ration-card':

                $rules += [
                    'customer_name'   => 'required|string|max:255',
                    'mobile'          => 'required|digits:10',
                    'aadhaar_number'  => 'nullable|digits:12',
                    'photo'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'document'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'family_members'  => 'nullable|integer|min:1',
                    'family_details'  => 'nullable|string',
                ];
                break;

            default:

                $rules['service_slug'][] = Rule::in([
                    'pm-kisan-registration',
                    'ayushman-card',
                    'income-certificate',
                    'domicile-niwas-certificate',
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