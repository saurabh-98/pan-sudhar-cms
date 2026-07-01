<?php

if (! function_exists('aadhaar_service_fields')) {

    function aadhaar_service_fields(string $serviceSlug): array
    {
        $kycFields = [

            [
                'name' => 'mobile',
                'label' => 'Aadhaar Registered Mobile Number',
                'type' => 'text',
                'required' => true,
            ],

            [
                'name' => 'alternate_mobile',
                'label' => 'Alternate Mobile Number',
                'type' => 'text',
            ],

            [
                'name' => 'aadhaar_number',
                'label' => 'Aadhaar Number',
                'type' => 'text',
                'required' => true,
            ],

            [
                'name' => 'aadhaar_card',
                'label' => 'Aadhaar Front & Back',
                'type' => 'file',
                'required' => true,
            ],

            [
                'name' => 'supportive_document',
                'label' => 'Supportive Document',
                'type' => 'file',
            ],

        ];

        return [

            'mobile-number-update' => array_merge([

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'new_mobile',
                    'label' => 'New Mobile Number',
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
                'name' => 'aadhaar_card',
                'label' => 'Aadhaar Front & Back',
                'type' => 'file',
                'required' => true,
            ],


            ], ),

            'name-correction' => array_merge([

                [
                    'name' => 'customer_name',
                    'label' => 'Current Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'new_name',
                    'label' => 'New Name',
                    'type' => 'text',
                    'required' => true,
                ],

            ], $kycFields),

            'dob-correction' => array_merge([

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'old_dob',
                    'label' => 'Old DOB',
                    'type' => 'date',
                ],

                [
                    'name' => 'new_dob',
                    'label' => 'New DOB',
                    'type' => 'date',
                ],

                [
                    'name' => 'dob_proof',
                    'label' => 'DOB Proof',
                    'type' => 'file',
                ],

            ], $kycFields),

            'address-update' => array_merge([

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'new_address',
                    'label' => 'New Address',
                    'type' => 'textarea',
                ],

                [
                    'name' => 'address_proof',
                    'label' => 'Address Proof',
                    'type' => 'file',
                ],

            ], $kycFields),

            'father-name-update' => array_merge([

                [
                    'name' => 'customer_name',
                    'label' => 'Current Father Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'father_name',
                    'label' => 'New Father Name',
                    'type' => 'text',
                ],

            ], $kycFields),

            'husband-name-update' => array_merge([

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'husband_name',
                    'label' => 'New Husband Name',
                    'type' => 'text',
                ],

            ], $kycFields),

            'gender-update' => array_merge([

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'gender',
                    'label' => 'Gender',
                    'type' => 'select',
                    'options' => [
                        'Male',
                        'Female',
                        'Transgender',
                    ],
                ],

            ], $kycFields),

            'email-update' => array_merge([

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'email',
                    'label' => 'New Email Address',
                    'type' => 'email',
                ],

            ], $kycFields),

            'biometric-update' => array_merge([

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'biometric_type',
                    'label' => 'Biometric Type',
                    'type' => 'select',
                    'options' => [
                        'Fingerprint',
                        'Iris',
                        'Face',
                    ],
                ],

            ], $kycFields),

            'child-aadhaar-enrollment' => [

                [
                    'name' => 'child_name',
                    'label' => 'Child Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'child_dob',
                    'label' => 'Child DOB',
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

            ],

            'new-aadhaar-apply' => [

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
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
                    'name' => 'mobile',
                    'label' => 'Mobile Number',
                    'type' => 'text',
                ],

                [
                    'name' => 'address',
                    'label' => 'Address',
                    'type' => 'textarea',
                ],

            ],

            'aadhaar-pvc-card' => array_merge([

                [
                    'name' => 'delivery_address',
                    'label' => 'Delivery Address',
                    'type' => 'textarea',
                ],

            ], $kycFields),


        ][$serviceSlug] ?? [];
    }
}