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

            'income-certificate' => [

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mobile',
                    'label' => 'Customer Mobile No.',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'state_id',
                    'label' => 'State',
                    'type' => 'state_dropdown',
                    'required' => true,
                ],

                [
                    'name' => 'district_id',
                    'label' => 'District',
                    'type' => 'district_dropdown',
                    'required' => true,
                ],

                [
                    'name' => 'aadhaar_number',
                    'label' => 'Aadhaar Number',
                    'type' => 'text',
                    'required' => true,
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
                    'name' => 'passport_photo',
                    'label' => 'Live Passport Size Photo',
                    'type' => 'file',
                ],

                [
                    'name' => 'aadhaar_card',
                    'label' => 'Aadhaar Card',
                    'type' => 'file',
                ],

                [
                    'name' => 'bank_passbook',
                    'label' => 'Govt. Bank Passbook',
                    'type' => 'file',
                ],

                [
                    'name' => 'address_proof',
                    'label' => 'E-Bill / Address Proof / 10th Marksheet',
                    'type' => 'file',
                ],

            
            ],
            /*
            |--------------------------------------------------------------------------
            | RESIDENCE CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'domicile-niwas-certificate' => [

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mobile',
                    'label' => 'Customer Mobile No.',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'state_id',
                    'label' => 'State',
                    'type' => 'state_dropdown',
                    'required' => true,
                ],

                [
                    'name' => 'district_id',
                    'label' => 'District',
                    'type' => 'district_dropdown',
                    'required' => true,
                ],

                [
                    'name' => 'aadhaar_number',
                    'label' => 'Aadhaar Number',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mother_name',
                    'label' => "Mother's Name",
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'passport_photo',
                    'label' => 'Live Passport Size Photo',
                    'type' => 'file',
                ],

                [
                    'name' => 'aadhaar_card',
                    'label' => 'Aadhaar Card',
                    'type' => 'file',
                ],

                [
                    'name' => 'bank_passbook',
                    'label' => 'Govt. Bank Passbook',
                    'type' => 'file',
                ],

                [
                    'name' => 'address_proof',
                    'label' => 'Address Proof (E-Bill / 10th Marksheet / Passport / DL / Voter ID / Water Bill / Telephone Bill)',
                    'type' => 'file',
                ],

            ],

            /*
            |--------------------------------------------------------------------------
            | CASTE CERTIFICATE
            |--------------------------------------------------------------------------
            */

          'caste-certificate' => [

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mobile',
                    'label' => 'Customer Mobile No.',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'state_id',
                    'label' => 'State',
                    'type' => 'state_dropdown',
                    'required' => true,
                ],

                [
                    'name' => 'district_id',
                    'label' => 'District',
                    'type' => 'district_dropdown',
                    'required' => true,
                ],

                [
                    'name' => 'aadhaar_number',
                    'label' => 'Aadhaar Number',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mother_name',
                    'label' => "Mother's Name",
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'certificate_type',
                    'label' => 'Certificate Type',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'Central English Wala',
                        'State',
                        'NCL',
                        'Creamy Layer',
                        'Jati Praman Patra',
                    ],
                ],

                [
                    'name' => 'document_type',
                    'label' => 'Document Type',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'With Document',
                        'Without Document',
                    ],
                ],

                [
                    'name' => 'caste_category',
                    'label' => 'Caste Category',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'SC',
                        'ST',
                        'OBC',
                        'EBC',
                    ],
                ],

                [
                    'name' => 'old_caste_certificate',
                    'label' => 'Old Caste Certificate',
                    'type' => 'file',
                ],

                [
                    'name' => 'passport_photo',
                    'label' => 'Live Passport Size Photo',
                    'type' => 'file',
                    'required' => true,
                ],

                [
                    'name' => 'aadhaar_card',
                    'label' => 'Aadhaar Card',
                    'type' => 'file',
                    'required' => true,
                ],

                [
                    'name' => 'bank_passbook',
                    'label' => 'Govt. Bank Passbook',
                    'type' => 'file',
                ],

                [
                    'name' => 'address_proof',
                    'label' => 'Address Proof (E-Bill / 10th Marksheet / Passport / DL / Voter ID / Water Bill / Telephone Bill)',
                    'type' => 'file',
                ],

                [
                    'name' => 'remarks',
                    'label' => 'Remarks / Purpose',
                    'type' => 'textarea',
                ],

            ],

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