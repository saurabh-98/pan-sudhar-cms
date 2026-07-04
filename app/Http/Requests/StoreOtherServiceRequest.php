<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOtherServiceRequest extends FormRequest
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

            case 'raj-patra':

                $rules += [
                    'customer_name'   => 'required|string|max:255',
                    'mobile'          => 'required|digits:10',
                    'email'           => 'nullable|email|max:255',
                    'aadhaar_number'  => 'nullable|digits:12',
                    'document'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;

            case 'gst-registration-filing':

                $rules += [
                    'customer_name'     => 'required|string|max:255',
                    'mobile'            => 'required|digits:10',
                    'email'             => 'nullable|email|max:255',
                    'aadhaar_number'    => 'nullable|digits:12',
                    'document'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'business_name'     => 'required|string|max:255',
                    'pan_number'        => 'required|string|max:20',
                    'business_address'  => 'required|string|max:1000',
                ];
                break;

            case 'food-licence':

                $rules += [
                    'customer_name'   => 'required|string|max:255',
                    'mobile'          => 'required|digits:10',
                    'email'           => 'nullable|email|max:255',
                    'aadhaar_number'  => 'nullable|digits:12',
                    'document'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'shop_name'       => 'required|string|max:255',
                    'business_type'   => 'required|string|max:255',
                ];
                break;

            case 'itr-filing-tds-refund':

                $rules += [
                    'customer_name'    => 'required|string|max:255',
                    'mobile'           => 'required|digits:10',
                    'email'            => 'nullable|email|max:255',
                    'aadhaar_number'   => 'nullable|digits:12',
                    'document'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'pan_number'       => 'required|string|max:20',
                    'assessment_year'  => 'required|string|max:20',
                ];
                break;

            case 'dsc-digital-signature':

                $rules += [
                    'customer_name'      => 'required|string|max:255',
                    'mobile'             => 'required|digits:10',
                    'email'              => 'nullable|email|max:255',
                    'aadhaar_number'     => 'nullable|digits:12',
                    'document'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'pan_number'         => 'required|string|max:20',
                    'organisation_name'  => 'nullable|string|max:255',
                ];
                break;

            case 'msme-registration':

                $rules += [
                    'customer_name'   => 'required|string|max:255',
                    'mobile'          => 'required|digits:10',
                    'email'           => 'nullable|email|max:255',
                    'aadhaar_number'  => 'nullable|digits:12',
                    'document'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'business_name'   => 'required|string|max:255',
                    'business_type'   => 'nullable|string|max:255',
                ];
                break;

            case 'import-export-certificate':

            case 'npci-aadhaar-seeding':

                $rules += [
                    'customer_name' => 'required|string|max:255',
                    'number'        => 'required|digits:10',
                ];
                break;

            case 'rent-agreement':

                $rules += [
                    'customer_name'    => 'required|string|max:255',
                    'mobile'           => 'required|digits:10',
                    'document'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'owner_name'       => 'required|string|max:255',
                    'tenant_name'      => 'required|string|max:255',
                    'property_address' => 'required|string|max:1000',
                ];
                break;

            case 'police-verification':

                $rules += [
                    'customer_name'         => 'required|string|max:255',
                    'mobile'                => 'required|digits:10',
                    'document'              => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                    'purpose'               => 'required|string|max:255',
                    'verification_address'  => 'required|string|max:1000',
                ];
                break;

            case 'driving-learning-license':

            case 'vehicle-chalan-payment':

            case 'rto-service':

                $rules += [
                    'customer_name' => 'required|string|max:255',
                    'mobile'        => 'required|digits:10',
                    'document'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;

            default:

                $rules['service_slug'][] = Rule::in([
                    'raj-patra',
                    'gst-registration-filing',
                    'food-licence',
                    'itr-filing-tds-refund',
                    'dsc-digital-signature',
                    'msme-registration',
                    'import-export-certificate',
                    'npci-aadhaar-seeding',
                    'rent-agreement',
                    'police-verification',
                    'driving-learning-license',
                    'vehicle-chalan-payment',
                    'rto-service',
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