<?php

if (! function_exists('bank_account_fields')) {

    function bank_account_fields(
        string $serviceSlug
    ): array {


    

        /*
        |--------------------------------------------------------------------------
        | BASE IDENTITY FIELDS
        |--------------------------------------------------------------------------
        | The bare minimum KYC fields every bank service needs. Each service
        | below extends this with its own specific fields rather than reusing
        | one identical form.
        */

        $baseFields = [

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

        ];

        /*
        |--------------------------------------------------------------------------
        | REUSABLE FIELD BLOCKS
        |--------------------------------------------------------------------------
        | Full-service banks share several fields (PAN, address, nominee, initial
        | deposit). Defined once here so each bank below only lists what actually
        | differs for it.
        */

           $customerName = [
            'name'     => 'customer_name',
            'label'    => 'Customer Name',
            'type'     => 'text',
            'required' => true,
        ];

        $customerMobile = [
            'name'     => 'mobile',
            'label'    => 'Mobile Number',
            'type'     => 'text',
            'required' => true,
        ];

        $aadhaarNumber = [
            'name'     => 'aadhaar_number',
            'label'    => 'Aadhaar Number',
            'type'     => 'text',
            'required' => false,
        ];

        $passportPhotoField = [
            'name'        => 'passport_size_photo',
            'label'       => 'Passport Size Photo Available?',
            'type'        => 'select',
            'placeholder' => 'Select',
            'options'     => [
                'yes' => 'Yes',
                'no'  => 'No',
            ],
            'required'    => false,
        ];

        $emailField = [
            'name'     => 'email',
            'label'    => 'Email Address',
            'type'     => 'email',
            'required' => false,
        ];

        $addressField = [
            'name'     => 'address',
            'label'    => 'Address',
            'type'     => 'textarea',
            'required' => true,
        ];

        $panNumberField = [
            'name'     => 'pan_number',
            'label'    => 'PAN Number',
            'type'     => 'text',
            'required' => false,
        ];

        $panCardField = [
            'name'     => 'pan_card',
            'label'    => 'PAN Card',
            'type'     => 'file',
            'required' => true,
        ];

        $initialDepositField = [
            'name'     => 'initial_deposit_amount',
            'label'    => 'Initial Deposit Amount',
            'type'     => 'number',
            'required' => false,
        ];


        $occupationField = [
            'name'     => 'occupation',
            'label'    => 'Occupation',
            'type'     => 'text',
            'required' => false,
        ];


        $nomineeFields = [
            [
                'name'     => 'nominee_name',
                'label'    => 'Nominee Name',
                'type'     => 'text',
                'required' => true,
            ],
            [
                'name'     => 'nominee_relation',
                'label'    => 'Nominee Relation',
                'type'     => 'text',
                'required' => true,
            ],
        ];

        return [

            /*
            |--------------------------------------------------------------------------
            | AIRTEL PAYMENTS BANK
            |--------------------------------------------------------------------------
            | Payments bank — zero balance, simplified Aadhaar e-KYC. No PAN or
            | nominee required at opening; only asks whether the mobile is
            | already an Airtel number, since the account gets linked to it.
            */

            'airtel-bank-account' => array_merge(
                $baseFields,
                [
                    [
                        'name' => 'is_airtel_number',
                        'label' => 'Is this an Airtel Mobile Number?',
                        'type' => 'select',
                        'options' => ['Yes', 'No'],
                        'required' => true,
                    ],
                    $emailField,
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | INDIAN BANK
            |--------------------------------------------------------------------------
            | Full-service savings account — PAN, address, initial deposit and
            | nominee are all mandatory.
            */

            'indian-bank' => array_merge([
                $customerName,
                $customerMobile,
                $aadhaarNumber,
                $passportPhotoField, 
                $panNumberField,
                
            ]),

            /*
            |--------------------------------------------------------------------------
            | INDIAN OVERSEAS BANK
            |--------------------------------------------------------------------------
            | Full-service savings account — additionally captures occupation
            | for risk categorisation.
            */

            'indian-overseas-bank' => array_merge(
                $baseFields,
                [
                    $panNumberField,
                    $panCardField,
                    $emailField,
                    $addressField,
                    $occupationField,
                    $initialDepositField,
                ],
                $nomineeFields
            ),

            /*
            |--------------------------------------------------------------------------
            | NSDL PAYMENT BANK
            |--------------------------------------------------------------------------
            | Payments bank — minimal KYC, no PAN/nominee/deposit needed.
            */

            'nsdl-payment-bank' => array_merge(
                $baseFields,
                [
                    $emailField,
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | JIO PAYMENT BANK
            |--------------------------------------------------------------------------
            | Payments bank — minimal KYC, similar to Airtel but no carrier
            | ownership question since a Jio SIM isn't a prerequisite here.
            */

            'jio-payment-bank' => array_merge(
                $baseFields,
                [
                    $emailField,
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | BANK OF BARODA
            |--------------------------------------------------------------------------
            | Full-service savings account with initial deposit and nominee.
            */

            'bank-of-baroda' => array_merge(
                $baseFields,
                [
                    $panNumberField,
                    $panCardField,
                    $emailField,
                    $addressField,
                    $initialDepositField,
                ],
                $nomineeFields
            ),

            /*
            |--------------------------------------------------------------------------
            | KOTAK BANK ACCOUNT
            |--------------------------------------------------------------------------
            | Full-service account that also offers instant Video KYC, so the
            | form asks whether the customer wants to opt into it.
            */

            'kotak-bank-account' => array_merge(
                $baseFields,
                [
                    $panNumberField,
                    $panCardField,
                    $emailField,
                    $addressField,
                    [
                        'name' => 'video_kyc_preference',
                        'label' => 'Opt for Instant Video KYC?',
                        'type' => 'select',
                        'options' => ['Yes', 'No'],
                        'required' => true,
                    ],
                    $initialDepositField,
                ],
                $nomineeFields
            ),

            /*
            |--------------------------------------------------------------------------
            | SBI / PNB BANK ACCOUNT
            |--------------------------------------------------------------------------
            | Full-service savings account — highest documentation: PAN,
            | occupation, preferred branch, deposit and nominee.
            */

            'sbi-pnb-bank-account' => array_merge(
                $baseFields,
                [
                    $panNumberField,
                    $panCardField,
                    $emailField,
                    $addressField,
                    $occupationField,
                   
                    $initialDepositField,
                ],
                $nomineeFields
            ),

        ][$serviceSlug] ?? [];
    }
}