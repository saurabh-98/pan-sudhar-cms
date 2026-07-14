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
                        'name' => 'aadhaar_card',
                        'label' => 'Aadhaar card',
                        'type' => 'file',
                    ],


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

            'dsc-digital-signature' => [

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
                    'name' => 'state',
                    'label' => 'State',
                    'type' => 'state_dropdown',
                    'required' => true,
                ],

                [
                    'name' => 'district',
                    'label' => 'District',
                    'type' => 'district_dropdown',
                    'required' => true,
                ],

                [
                    'name' => 'dsc_class',
                    'label' => 'DSC Class',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'Class 1 (Normal Paper Sign)',
                        'Class 2 (Government Portal, Startup etc.)',
                        'Class 3 (Tender, MCA, GST, Company ITR)'
                    ],
                ],

                [
                    'name' => 'organisation_name',
                    'label' => 'Organisation Name',
                    'type' => 'text',
                ],

                [
                    'name' => 'email',
                    'label' => 'Email ID',
                    'type' => 'email',
                ],

                [
                    'name' => 'remarks',
                    'label' => 'Remarks',
                    'type' => 'textarea',
                ],

            ],

            /*
            |--------------------------------------------------------------------------
            | MSME REGISTRATION
            |--------------------------------------------------------------------------
            */

            'msme-registration' => [

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
                    'name' => 'bank_details',
                    'label' => 'Upload Bank Details',
                    'type' => 'file',
                    'required' => true,
                ],

                [
                    'name' => 'email',
                    'label' => 'Email ID',
                    'type' => 'email',
                ],

                [
                    'name' => 'business_name',
                    'label' => 'Business Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'business_category',
                    'label' => 'Business Category',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'business_address',
                    'label' => 'Business Address',
                    'type' => 'textarea',
                    'required' => true,
                ],

                [
                    'name' => 'purpose',
                    'label' => 'Purpose',
                    'type' => 'textarea',
                ],

                [
                    'name' => 'aadhaar_card',
                    'label' => 'Upload Aadhaar Card',
                    'type' => 'file',
                    'required' => true,
                ],

                [
                    'name' => 'pan_card',
                    'label' => 'Upload PAN Card',
                    'type' => 'file',
                    'required' => true,
                ],

            ],

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

            'rent-agreement' => [

                [
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'mobile',
                    'label' => 'Customer Mobile',
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
                    'name' => 'stamp_paper_value',
                    'label' => 'Stamp Paper Value',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        '10',
                        '50',
                        '100',
                    ],
                ],

                [
                    'name' => 'aadhaar_card',
                    'label' => 'Customer Aadhaar Front & Back',
                    'type' => 'file',
                    'required' => true,
                ],

                [
                    'name' => 'agreement_date',
                    'label' => 'Date of Rent Agreement',
                    'type' => 'date',
                    'required' => true,
                ],

                [
                    'name' => 'monthly_rent',
                    'label' => 'Monthly Rent Amount',
                    'type' => 'number',
                    'required' => true,
                ],

                // Landlord Details

                [
                    'name' => 'owner_name',
                    'label' => 'Landlord / Owner Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'owner_father_name',
                    'label' => 'Landlord Father Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'owner_address',
                    'label' => 'Landlord Full Address',
                    'type' => 'textarea',
                    'required' => true,
                ],

                [
                    'name' => 'owner_id_proof',
                    'label' => 'Landlord Address / ID Proof',
                    'type' => 'file',
                    'required' => true,
                ],

                // Tenant Details

                [
                    'name' => 'tenant_name',
                    'label' => 'Tenant Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'tenant_father_name',
                    'label' => 'Tenant Father Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'tenant_address',
                    'label' => 'Tenant Full Address',
                    'type' => 'textarea',
                    'required' => true,
                ],

                [
                    'name' => 'tenant_id_proof',
                    'label' => 'Tenant Address / ID Proof',
                    'type' => 'file',
                    'required' => true,
                ],

            ],

            /*
            |--------------------------------------------------------------------------
            | POLICE VERIFICATION
            |--------------------------------------------------------------------------
            */

            'police-verification' => array_merge(
                
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
                        'name' => 'document',
                        'label' => 'Passpot Size Photo',
                        'type' => 'file',
                    ],

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


              'driving-learning-license' => array_merge(
                
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
                        'label' => 'Aadhaar Card',
                        'type' => 'file',
                    ],
                
                    [
                        'name' => 'document',
                        'label' => 'Signature',
                        'type' => 'file',
                    ],

                    [
                        'name' => 'document',
                        'label' => 'Photo',
                        'type' => 'file',
                    ],

                   
                ]
            ),

             'vehicle-chalan-payment' => array_merge(
                
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
                        'label' => 'Aadhaar Card',
                        'type' => 'file',
                    ],
                
                   
                    [
                        'name' => 'document',
                        'label' => 'Upload Chalan Copy',
                        'type' => 'file',
                    ],

                   
                ]
            ),


                'vehicle-chalan-payment' => array_merge(
                
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
                        'label' => 'Aadhaar Card',
                        'type' => 'file',
                    ],
                
                   
                    [
                        'name' => 'document',
                        'label' => 'Upload Chalan Copy',
                        'type' => 'file',
                    ],

                   
                ]
            ),
            
             'rto-service' => array_merge(
                
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
                        'label' => 'Aadhaar Card',
                        'type' => 'file',
                    ],
                
                    [
                        'name' => 'document',
                        'label' => 'Signature',
                        'type' => 'file',
                    ],

                    [
                        'name' => 'document',
                        'label' => 'Photo',
                        'type' => 'file',
                    ],

                   
                ]
            ),

        ][$serviceSlug] ?? [];
    }
}