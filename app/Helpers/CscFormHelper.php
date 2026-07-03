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
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mobile',
                    'label' => 'Customer Mobile Number',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'aadhaar_number',
                    'label' => 'Aadhaar Number',
                    'type' => 'text',
                ],

               
                [
                    'name' => 'khatuni',
                    'label' => 'Khatuni',
                    'type' => 'file',
                ],

            ],),

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
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mobile',
                    'label' => 'Customer Mobile Number',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'aadhaar_number',
                    'label' => 'Aadhaar Number',
                    'type' => 'text',
                ],

                 [
                    'name' => 'mother_name',
                    'label' => "Mother's Name",
                    'type' => 'text',
                    'required' => true,
                ],

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


                [
                    'name' => 'photo',
                    'label' => 'Live Passport Size Photo',
                    'type' => 'file',
                ],

                [
                    'name' => 'document',
                    'label' => 'Aadhaar Card',
                    'type' => 'file',
                ],

                 [
                    'name' => 'document',
                    'label' => 'Govt. Bank Passbook ',
                    'type' => 'file',
                ],
                    
            ],),

            /*
            |--------------------------------------------------------------------------
            | RESIDENCE CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'domicile-niwas-certificate' => array_merge([


                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mobile',
                    'label' => 'Customer Mobile Number',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'aadhaar_number',
                    'label' => 'Aadhaar Number',
                    'type' => 'text',
                ],

                 [
                    'name' => 'mother_name',
                    'label' => "Mother's Name",
                    'type' => 'text',
                    'required' => true,
                ],

                 [
                    'name' => 'aadhaar_card',
                    'label' => 'Aadhaar Card',
                    'type' => 'file',
                ],

                [
                    'name' => 'residence_proof',
                    'label' => 'Address Proof',
                    'type' => 'file',
                ],

                [
                    'name' => 'address',
                    'label' => 'Full Address',
                    'type' => 'textarea',
                ],

                

               

            ],),

            /*
            |--------------------------------------------------------------------------
            | CASTE CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'caste-certificate' => array_merge([


                 [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mobile',
                    'label' => 'Customer Mobile Number',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'aadhaar_number',
                    'label' => 'Aadhaar Number',
                    'type' => 'text',
                ],

                [
                    'name' => 'mother_name',
                    'label' => "Mother's Name",
                    'type' => 'text',
                    'required' => true,
                ],

                 [
                    'name' => 'aadhaar_card',
                    'label' => 'Aadhaar Card',
                    'type' => 'file',
                ],

                  [
                    'name' => 'photo',
                    'label' => 'Passport Size Photo',
                    'type' => 'file',
                ],

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
                    'label' => 'Old Caste Certificate',
                    'type' => 'file',
                ],

            ],),

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
                    'name' => 'gender',
                    'label' => 'Gender',
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
                    'label' => "Father's Name",
                    'type' => 'text',
                ],

                 [
                    'name' => 'father_aadhaar_number',
                    'label' => "Father's Aadhaar Number",
                    'type' => 'number',
                ],

                [
                    'name' => 'mother_name',
                    'label' => "Mother's Name",
                    'type' => 'text',
                ],

                 [
                    'name' => 'mother_aadhaar_number',
                    'label' => "Mother's Aadhaar Number",
                    'type' => 'number',
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
                    'name' => 'occupation',
                    'label' => 'Occupation',
                    'type' => 'text',
                ],

                [
                    'name' => 'bank_account',
                    'label' => 'Bank Account Number',
                    'type' => 'text',
                ],

                [
                    'name' => 'bank_passbook',
                    'label' => 'Bank Passbook',
                    'type' => 'file',
                ],

                [
                    'name' => 'photo',
                    'label' => 'Passport Size Photo',
                    'type' => 'file',
                ],

                [
                    'name' => 'aadhaar_card',
                    'label' => 'Aadhaar Card',
                    'type' => 'file',
                ],

                

            ],),

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