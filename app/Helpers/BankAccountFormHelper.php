<?php

if (! function_exists('bank_account_fields')) {

    function bank_account_fields(
        string $serviceSlug
    ): array {

        $commonFields = [

            [
                'name' => 'customer_name',
                'label' => 'Customer Name',
                'type' => 'text',
                'required' => true,
            ],

            [
                'name' => 'mobile',
                'label' => 'Mobile Number',
                'type' => 'text',
                'required' => true,
            ],

            [
                'name' => 'aadhaar_number',
                'label' => 'Aadhaar Number',
                'type' => 'text',
                'required' => true,
            ],

            [
                'name' => 'pan_number',
                'label' => 'PAN Number',
                'type' => 'text',
            ],

            [
                'name' => 'email',
                'label' => 'Email Address',
                'type' => 'email',
            ],

            [
                'name' => 'address',
                'label' => 'Address',
                'type' => 'textarea',
            ],

            [
                'name' => 'photo',
                'label' => 'Passport Size Photo',
                'type' => 'file',
            ],

            [
                'name' => 'aadhaar_front',
                'label' => 'Aadhaar Front',
                'type' => 'file',
            ],

            [
                'name' => 'aadhaar_back',
                'label' => 'Aadhaar Back',
                'type' => 'file',
            ],

            [
                'name' => 'pan_card',
                'label' => 'PAN Card',
                'type' => 'file',
            ],

        ];

        return [

            /*
            |--------------------------------------------------------------------------
            | KOTAK BANK ZERO BALANCE
            |--------------------------------------------------------------------------
            */

            'kotak-bank-zero-balance' => $commonFields,

            /*
            |--------------------------------------------------------------------------
            | INDIA POST PAYMENT BANK
            |--------------------------------------------------------------------------
            */

            'india-post-payment-bank' => $commonFields,

            /*
            |--------------------------------------------------------------------------
            | NSDL PAYMENT BANK
            |--------------------------------------------------------------------------
            */

            'nsdl-payment-bank' => $commonFields,

            /*
            |--------------------------------------------------------------------------
            | AIRTEL PAYMENT BANK
            |--------------------------------------------------------------------------
            */

            'airtel-payment-bank' => $commonFields,

            /*
            |--------------------------------------------------------------------------
            | BANK OF INDIA
            |--------------------------------------------------------------------------
            */

            'bank-of-india' => $commonFields,

            /*
            |--------------------------------------------------------------------------
            | PNB BANK
            |--------------------------------------------------------------------------
            */

            'pnb-bank' => $commonFields,

            /*
            |--------------------------------------------------------------------------
            | INDIAN BANK
            |--------------------------------------------------------------------------
            */

            'indian-bank' => $commonFields,

            /*
            |--------------------------------------------------------------------------
            | INDIAN OVERSEAS BANK
            |--------------------------------------------------------------------------
            */

            'indian-overseas-bank' => $commonFields,

        ][$serviceSlug] ?? [];
    }
}