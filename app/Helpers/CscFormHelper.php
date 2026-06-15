<?php

if (! function_exists('csc_service_fields')) {

    function csc_service_fields(string $serviceSlug): array
    {
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
            ],

            [
                'name' => 'photo',
                'label' => 'Passport Size Photo',
                'type' => 'file',
            ],

            [
                'name' => 'document',
                'label' => 'Supporting Document',
                'type' => 'file',
            ],

        ];

        return [

            /*
            |--------------------------------------------------------------------------
            | PM KISAN
            |--------------------------------------------------------------------------
            */

            'pm-kisan-registration' => array_merge([

                [
                    'name' => 'farmer_name',
                    'label' => 'Farmer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'land_details',
                    'label' => 'Land Details',
                    'type' => 'textarea',
                ],

                [
                    'name' => 'bank_account',
                    'label' => 'Bank Account Number',
                    'type' => 'text',
                ],

                [
                    'name' => 'passbook',
                    'label' => 'Bank Passbook',
                    'type' => 'file',
                ],

            ], $commonFields),

            /*
            |--------------------------------------------------------------------------
            | AYUSHMAN CARD
            |--------------------------------------------------------------------------
            */

            'ayushman-card' => array_merge([

                [
                    'name' => 'family_id',
                    'label' => 'Family ID',
                    'type' => 'text',
                ],

                [
                    'name' => 'ration_card',
                    'label' => 'Ration Card',
                    'type' => 'file',
                ],

            ], $commonFields),

            /*
            |--------------------------------------------------------------------------
            | INCOME CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'income-certificate' => array_merge([

                [
                    'name' => 'occupation',
                    'label' => 'Occupation',
                    'type' => 'text',
                ],

                [
                    'name' => 'annual_income',
                    'label' => 'Annual Income',
                    'type' => 'number',
                ],

            ], $commonFields),

            /*
            |--------------------------------------------------------------------------
            | RESIDENCE CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'residence-certificate' => array_merge([

                [
                    'name' => 'address',
                    'label' => 'Full Address',
                    'type' => 'textarea',
                ],

                [
                    'name' => 'residence_proof',
                    'label' => 'Residence Proof',
                    'type' => 'file',
                ],

            ], $commonFields),

            /*
            |--------------------------------------------------------------------------
            | CASTE CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'caste-certificate' => array_merge([

                [
                    'name' => 'category',
                    'label' => 'Category',
                    'type' => 'select',
                    'options' => [
                        'SC',
                        'ST',
                        'OBC',
                        'EBC',
                    ],
                ],

                [
                    'name' => 'caste_proof',
                    'label' => 'Caste Proof',
                    'type' => 'file',
                ],

            ], $commonFields),

            /*
            |--------------------------------------------------------------------------
            | BIRTH CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'birth-certificate' => [

                [
                    'name' => 'child_name',
                    'label' => 'Child Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'dob',
                    'label' => 'Date Of Birth',
                    'type' => 'date',
                ],

                [
                    'name' => 'father_name',
                    'label' => 'Father Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'mother_name',
                    'label' => 'Mother Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'hospital_certificate',
                    'label' => 'Hospital Certificate',
                    'type' => 'file',
                ],

            ],

            /*
            |--------------------------------------------------------------------------
            | DEATH CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'death-certificate' => [

                [
                    'name' => 'deceased_name',
                    'label' => 'Deceased Person Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'death_date',
                    'label' => 'Death Date',
                    'type' => 'date',
                ],

                [
                    'name' => 'death_proof',
                    'label' => 'Death Proof',
                    'type' => 'file',
                ],

            ],

            /*
            |--------------------------------------------------------------------------
            | LABOUR CARD
            |--------------------------------------------------------------------------
            */

            'labour-card' => array_merge([

                [
                    'name' => 'occupation',
                    'label' => 'Occupation',
                    'type' => 'text',
                ],

                [
                    'name' => 'experience_year',
                    'label' => 'Experience',
                    'type' => 'number',
                ],

            ], $commonFields),

            /*
            |--------------------------------------------------------------------------
            | E-SHRAM
            |--------------------------------------------------------------------------
            */

            'e-shram-card' => array_merge([

                [
                    'name' => 'occupation',
                    'label' => 'Occupation',
                    'type' => 'text',
                ],

                [
                    'name' => 'bank_account',
                    'label' => 'Bank Account Number',
                    'type' => 'text',
                ],

            ], $commonFields),

            /*
            |--------------------------------------------------------------------------
            | RATION CARD
            |--------------------------------------------------------------------------
            */

            'ration-card' => array_merge([

                [
                    'name' => 'family_members',
                    'label' => 'Family Members',
                    'type' => 'number',
                ],

                [
                    'name' => 'family_details',
                    'label' => 'Family Details',
                    'type' => 'textarea',
                ],

            ], $commonFields),

        ][$serviceSlug] ?? [];
    }
}