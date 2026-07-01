<?php

if (! function_exists('voter_id_fields')) {

    function voter_id_fields(string $serviceSlug): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | NEW VOTER ID CARD APPLY (₹150)
            |--------------------------------------------------------------------------
            */

            'new-voter-id' => [

                [
                    'name' => 'applicant_name',
                    'label' => 'Applicant Name',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'father_name',
                    'label' => 'Father Name',
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
                    'name' => 'dob',
                    'label' => 'Date Of Birth',
                    'type' => 'date',
                    'required' => true,
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

                [
                    'name' => 'address',
                    'label' => 'Full Address',
                    'type' => 'textarea',
                    'required' => true,
                ],

                [
                    'name' => 'district',
                    'label' => 'District',
                    'type' => 'text',
                ],

                [
                    'name' => 'state',
                    'label' => 'State',
                    'type' => 'text',
                ],

                [
                    'name' => 'pincode',
                    'label' => 'Pincode',
                    'type' => 'text',
                ],

                [
                    'name' => 'photo',
                    'label' => 'Passport Size Photo',
                    'type' => 'file',
                    'required' => true,
                ],

                [
                    'name' => 'age_proof',
                    'label' => 'Age Proof',
                    'type' => 'file',
                    'required' => true,
                ],

                [
                    'name' => 'address_proof',
                    'label' => 'Address Proof',
                    'type' => 'file',
                    'required' => true,
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | VOTER ID CORRECTION (₹100)
            |--------------------------------------------------------------------------
            */

            'voter-id-correction' => [

                [
                    'name' => 'epic_number',
                    'label' => 'EPIC Number',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'applicant_name',
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
                    'name' => 'correction_details',
                    'label' => 'Correction Details',
                    'type' => 'textarea',
                    'required' => true,
                ],

                [
                    'name' => 'supporting_document',
                    'label' => 'Supporting Document',
                    'type' => 'file',
                    'required' => true,
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | MOBILE NUMBER UPDATE (₹100)
            |--------------------------------------------------------------------------
            */

            'voter-id-mobile-update' => [

                [
                    'name' => 'epic_number',
                    'label' => 'EPIC Number',
                    'type' => 'text',
                    'required' => true,
                ],

                [
                    'name' => 'old_mobile',
                    'label' => 'Old Mobile Number',
                    'type' => 'text',
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
                ],

                [
                    'name' => 'identity_proof',
                    'label' => 'Identity Proof',
                    'type' => 'file',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | ADDRESS UPDATE (₹100)
            |--------------------------------------------------------------------------
            */

            'voter-id-address-update' => [

                [
                    'name' => 'epic_number',
                    'label' => 'EPIC Number',
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
                    'name' => 'new_address',
                    'label' => 'New Address',
                    'type' => 'textarea',
                    'required' => true,
                ],

                [
                    'name' => 'district',
                    'label' => 'District',
                    'type' => 'text',
                ],

                [
                    'name' => 'state',
                    'label' => 'State',
                    'type' => 'text',
                ],

                [
                    'name' => 'pincode',
                    'label' => 'Pincode',
                    'type' => 'text',
                ],

                [
                    'name' => 'address_proof',
                    'label' => 'Address Proof',
                    'type' => 'file',
                    'required' => true,
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | DOB UPDATE (₹100)
            |--------------------------------------------------------------------------
            */

            'voter-id-dob-update' => [

                [
                    'name' => 'epic_number',
                    'label' => 'EPIC Number',
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
                    'name' => 'current_dob',
                    'label' => 'Current DOB',
                    'type' => 'date',
                ],

                [
                    'name' => 'new_dob',
                    'label' => 'Correct DOB',
                    'type' => 'date',
                    'required' => true,
                ],

                [
                    'name' => 'dob_proof',
                    'label' => 'DOB Proof',
                    'type' => 'file',
                    'required' => true,
                ],
            ],

        ][$serviceSlug] ?? [];
    }
}