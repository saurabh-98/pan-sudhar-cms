<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBankAccountServiceRequest extends FormRequest
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
    | SIMPLE KYC BANKS
    |--------------------------------------------------------------------------
    */

    case 'airtel-bank-account':

        $rules += [

            'customer_name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'aadhaar_number' => 'required|digits:12',

            'is_airtel_number' => 'required|in:Yes,No',

            'email' => 'nullable|email|max:255',

            'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        break;

    case 'nsdl-payment-bank':

    case 'jio-payment-bank':

        $rules += [

            'customer_name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'aadhaar_number' => 'required|digits:12',

            'email' => 'nullable|email|max:255',

            'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        break;

    /*
    |--------------------------------------------------------------------------
    | FULL KYC BANKS
    |--------------------------------------------------------------------------
    */

    case 'indian-bank':

    case 'bank-of-baroda':

        $rules += [

            'customer_name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'aadhaar_number' => 'required|digits:12',

            'pan_number' => 'required|string|max:20',
            'pan_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:1000',

            'preferred_branch' => 'required|string|max:255',

            'initial_deposit_amount' => 'nullable|numeric|min:0',

            'nominee_name' => 'required|string|max:255',
            'nominee_relation' => 'required|string|max:255',

            'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        break;

    case 'indian-overseas-bank':

        $rules += [

            'customer_name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'aadhaar_number' => 'required|digits:12',

            'pan_number' => 'required|string|max:20',
            'pan_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:1000',

            'occupation' => 'required|string|max:255',

            'initial_deposit_amount' => 'nullable|numeric|min:0',

            'nominee_name' => 'required|string|max:255',
            'nominee_relation' => 'required|string|max:255',

            'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        break;

    case 'kotak-bank-account':

        $rules += [

            'customer_name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'aadhaar_number' => 'required|digits:12',

            'pan_number' => 'required|string|max:20',
            'pan_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:1000',

            'video_kyc_preference' => 'required|in:Yes,No',

            'initial_deposit_amount' => 'nullable|numeric|min:0',

            'nominee_name' => 'required|string|max:255',
            'nominee_relation' => 'required|string|max:255',

            'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        break;

    case 'sbi-pnb-bank-account':

        $rules += [

            'customer_name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'aadhaar_number' => 'required|digits:12',

            'pan_number' => 'required|string|max:20',
            'pan_card' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',

            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:1000',

            'occupation' => 'required|string|max:255',

            'preferred_branch' => 'required|string|max:255',

            'initial_deposit_amount' => 'nullable|numeric|min:0',

            'nominee_name' => 'required|string|max:255',
            'nominee_relation' => 'required|string|max:255',

            'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhaar_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        break;

    default:

        $rules['service_slug'][] = Rule::in([

            'airtel-bank-account',
            'indian-bank',
            'indian-overseas-bank',
            'nsdl-payment-bank',
            'jio-payment-bank',
            'bank-of-baroda',
            'kotak-bank-account',
            'sbi-pnb-bank-account',

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