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
                'required' => true,
            ],

            [
                'name' => 'aadhaar_front',
                'label' => 'Aadhaar Front',
                'type' => 'file',
                'required' => true,
            ],

            [
                'name' => 'aadhaar_back',
                'label' => 'Aadhaar Back',
                'type' => 'file',
                'required' => true,
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
            | NEW BANK ACCOUNT
            |--------------------------------------------------------------------------
            */

            'new-bank-account' => $commonFields,

            /*
            |--------------------------------------------------------------------------
            | ACCOUNT CLOSURE
            |--------------------------------------------------------------------------
            */

            'account-closure' => array_merge(
                $commonFields,
                [
                    [
                        'name' => 'account_number',
                        'label' => 'Account Number',
                        'type' => 'text',
                        'required' => true,
                    ],
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | MOBILE NUMBER UPDATE
            |--------------------------------------------------------------------------
            */

            'bank-mobile-update' => array_merge(
                $commonFields,
                [
                    [
                        'name' => 'new_mobile_number',
                        'label' => 'New Mobile Number',
                        'type' => 'text',
                        'required' => true,
                    ],
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | ADDRESS UPDATE
            |--------------------------------------------------------------------------
            */

            'bank-address-update' => array_merge(
                $commonFields,
                [
                    [
                        'name' => 'new_address',
                        'label' => 'New Address',
                        'type' => 'textarea',
                        'required' => true,
                    ],
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | KYC UPDATE
            |--------------------------------------------------------------------------
            */

            'kyc-update' => array_merge(
                $commonFields,
                [
                    [
                        'name' => 'kyc_document',
                        'label' => 'KYC Document',
                        'type' => 'file',
                        'required' => true,
                    ],
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | NOMINEE UPDATE
            |--------------------------------------------------------------------------
            */

            'nominee-update' => array_merge(
                $commonFields,
                [
                    [
                        'name' => 'nominee_name',
                        'label' => 'Nominee Name',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'nominee_relation',
                        'label' => 'Nominee Relation',
                        'type' => 'text',
                        'required' => true,
                    ],
                ]
            ),

        ][$serviceSlug] ?? [];
    }
}