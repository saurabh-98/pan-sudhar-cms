<?php

if (! function_exists('other_service_fields')) {

    function other_service_fields(
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
                'label' => 'Customer Mobile Number',
                'type' => 'text',
                'required' => true,
            ],

            [
                'name' => 'email',
                'label' => 'Email Address',
                'type' => 'email',
            ],

            [
                'name' => 'aadhaar_number',
                'label' => 'Aadhaar Number',
                'type' => 'text',
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
            | RAJ PATRA
            |--------------------------------------------------------------------------
            */

            'raj-patra' => array_merge(
                $commonFields
                           ),

            /*
            |--------------------------------------------------------------------------
            | GST REGISTRATION / FILING
            |--------------------------------------------------------------------------
            */

            'gst-registration-filing' => array_merge(
                $commonFields,
                [

                    [
                        'name' => 'document',
                        'label' => 'Rent Agreement',
                        'type' => 'file',
                    ],

                    [
                        'name' => 'business_name',
                        'label' => 'Business Name',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'pan_number',
                        'label' => 'PAN Number',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'business_address',
                        'label' => 'Business Address',
                        'type' => 'textarea',
                        'required' => true,
                    ],


                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | FOOD LICENCE
            |--------------------------------------------------------------------------
            */

            'food-licence' => array_merge(
                $commonFields,
                [

                     [
                        'name' => 'document',
                        'label' => 'Rent Agreement',
                        'type' => 'file',
                    ],

                    [
                        'name' => 'shop_name',
                        'label' => 'Shop Name',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'business_type',
                        'label' => 'Business Type',
                        'type' => 'text',
                        'required' => true,
                    ],
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | ITR FILING / TDS REFUND
            |--------------------------------------------------------------------------
            */

            'itr-filing-tds-refund' => array_merge(
                $commonFields,
                [
                    [
                        'name' => 'pan_number',
                        'label' => 'PAN Number',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'assessment_year',
                        'label' => 'Assessment Year',
                        'type' => 'text',
                        'required' => true,
                    ],
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | DSC DIGITAL SIGNATURE
            |--------------------------------------------------------------------------
            */

            'dsc-digital-signature' => array_merge(
                $commonFields,
                [
                    [
                        'name' => 'pan_number',
                        'label' => 'PAN Number',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'organisation_name',
                        'label' => 'Organisation Name',
                        'type' => 'text',
                    ],
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | MSME REGISTRATION
            |--------------------------------------------------------------------------
            */

            'msme-registration' => array_merge(
                $commonFields,
                [
                    [
                        'name' => 'business_name',
                        'label' => 'Business Name',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'business_type',
                        'label' => 'Business Type',
                        'type' => 'text',
                    ],
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | IMPORT EXPORT CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'npci-aadhaar-seeding' => array_merge(
            
                [
                    [
                        'name' => 'customer_name',
                        'label' => 'Customer name',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'number',
                        'label' => 'Customer Mobile Number',
                        'type' => 'text',
                        'required' => true,
                    ],
                ]
            ),


             'import-export-certificate' => array_merge(
            
                [
                    [
                        'name' => 'customer_name',
                        'label' => 'Customer name',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'number',
                        'label' => 'Customer Mobile Number',
                        'type' => 'text',
                        'required' => true,
                    ],
                ]
            ),


             

            /*
            |--------------------------------------------------------------------------
            | RENT AGREEMENT
            |--------------------------------------------------------------------------
            */

            'rent-agreement' => array_merge(
                [

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
                        'name' => 'document',
                        'label' => 'Supporting Document',
                        'type' => 'file',
                    ],

                    [
                        'name' => 'owner_name',
                        'label' => 'Owner Name',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'tenant_name',
                        'label' => 'Tenant Name',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'property_address',
                        'label' => 'Property Address',
                        'type' => 'textarea',
                        'required' => true,
                    ],
                ]
            ),

            /*
            |--------------------------------------------------------------------------
            | POLICE VERIFICATION
            |--------------------------------------------------------------------------
            */

            'police-verification' => array_merge(
                $commonFields,
                [
                    [
                        'name' => 'purpose',
                        'label' => 'Purpose',
                        'type' => 'text',
                        'required' => true,
                    ],

                    [
                        'name' => 'verification_address',
                        'label' => 'Verification Address',
                        'type' => 'textarea',
                        'required' => true,
                    ],
                ]
            ),

        ][$serviceSlug] ?? [];
    }
}