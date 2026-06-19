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
            | KOTAK BANK ZERO BALANCE
            |--------------------------------------------------------------------------
            */

            case 'kotak-bank-zero-balance':

            /*
            |--------------------------------------------------------------------------
            | INDIA POST PAYMENT BANK
            |--------------------------------------------------------------------------
            */

            case 'india-post-payment-bank':

            /*
            |--------------------------------------------------------------------------
            | NSDL PAYMENT BANK
            |--------------------------------------------------------------------------
            */

            case 'nsdl-payment-bank':

            /*
            |--------------------------------------------------------------------------
            | AIRTEL PAYMENT BANK
            |--------------------------------------------------------------------------
            */

            case 'airtel-payment-bank':

            /*
            |--------------------------------------------------------------------------
            | BANK OF INDIA
            |--------------------------------------------------------------------------
            */

            case 'bank-of-india':

            /*
            |--------------------------------------------------------------------------
            | PNB BANK
            |--------------------------------------------------------------------------
            */

            case 'pnb-bank':

            /*
            |--------------------------------------------------------------------------
            | INDIAN BANK
            |--------------------------------------------------------------------------
            */

            case 'indian-bank':

            /*
            |--------------------------------------------------------------------------
            | INDIAN OVERSEAS BANK
            |--------------------------------------------------------------------------
            */

            case 'indian-overseas-bank':

                $rules += [

                    'customer_name' => 'required|string|max:255',

                    'mobile' => 'required|digits:10',

                    'aadhaar_number' => 'required|digits:12',

                    'pan_number' => 'nullable|string|max:20',

                    'email' => 'nullable|email|max:255',

                    'address' => 'required|string|max:1000',

                    'photo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                    'aadhaar_front' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                    'aadhaar_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                    'pan_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',

                ];

            break;

            default:

                $rules['service_slug'][] = Rule::in([

                    'kotak-bank-zero-balance',

                    'india-post-payment-bank',

                    'nsdl-payment-bank',

                    'airtel-payment-bank',

                    'bank-of-india',

                    'pnb-bank',

                    'indian-bank',

                    'indian-overseas-bank',

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