<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Charge;
use Illuminate\Support\Facades\DB;

class ChargeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Charge::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Charge::insert([

            /*
            |--------------------------------------------------------------------------
            | PAN SERVICES
            |--------------------------------------------------------------------------
            */

            [
                'name'        => 'New PAN Apply',
                'code'        => 'new_pan_apply',
                'type'        => 'fixed',
                'value'       => 107,
                'description' => 'New PAN application charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'PAN Correction',
                'code'        => 'pan_correction',
                'type'        => 'fixed',
                'value'       => 50,
                'description' => 'PAN correction charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Company PAN',
                'code'        => 'company_pan',
                'type'        => 'fixed',
                'value'       => 150,
                'description' => 'Company PAN service charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'PAN Verify',
                'code'        => 'pan_verify',
                'type'        => 'fixed',
                'value'       => 20,
                'description' => 'PAN verification charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            /*
            |--------------------------------------------------------------------------
            | ITR SERVICES
            |--------------------------------------------------------------------------
            */

            [
                'name'        => 'File ITR',
                'code'        => 'file_itr',
                'type'        => 'fixed',
                'value'       => 299,
                'description' => 'ITR filing charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            /*
            |--------------------------------------------------------------------------
            | WALLET SERVICES
            |--------------------------------------------------------------------------
            */

            [
                'name'        => 'Wallet Recharge Fee',
                'code'        => 'wallet_recharge_fee',
                'type'        => 'percentage',
                'value'       => 2,
                'description' => 'Wallet recharge fee percentage',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            /*
            |--------------------------------------------------------------------------
            | PLATFORM SERVICES
            |--------------------------------------------------------------------------
            */

            [
                'name'        => 'GST',
                'code'        => 'gst',
                'type'        => 'percentage',
                'value'       => 18,
                'description' => 'GST percentage',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            /*
            |--------------------------------------------------------------------------
            | AADHAAR SERVICES
            |--------------------------------------------------------------------------
            */

            [
                'name'        => 'Mobile Number Update',
                'code'        => 'mobile_number_update',
                'type'        => 'fixed',
                'value'       => 50,
                'description' => 'Mobile Number Update Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Name Correction',
                'code'        => 'name_correction',
                'type'        => 'fixed',
                'value'       => 50,
                'description' => 'Name Correction Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'DOB Correction',
                'code'        => 'dob_correction',
                'type'        => 'fixed',
                'value'       => 75,
                'description' => 'Date Of Birth Correction Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Address Update',
                'code'        => 'address_update',
                'type'        => 'fixed',
                'value'       => 50,
                'description' => 'Address Update Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Father Name Update',
                'code'        => 'father_name_update',
                'type'        => 'fixed',
                'value'       => 50,
                'description' => 'Father Name Update Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Husband Name Update',
                'code'        => 'husband_name_update',
                'type'        => 'fixed',
                'value'       => 50,
                'description' => 'Husband Name Update Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Gender Update',
                'code'        => 'gender_update',
                'type'        => 'fixed',
                'value'       => 50,
                'description' => 'Gender Update Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Email Update',
                'code'        => 'email_update',
                'type'        => 'fixed',
                'value'       => 50,
                'description' => 'Email Update Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Biometric Update',
                'code'        => 'biometric_update',
                'type'        => 'fixed',
                'value'       => 100,
                'description' => 'Biometric Update Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Child Aadhaar Enrollment',
                'code'        => 'child_aadhaar_enrollment',
                'type'        => 'fixed',
                'value'       => 100,
                'description' => 'Child Aadhaar Enrollment Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'New Aadhaar Apply',
                'code'        => 'new_aadhaar_apply',
                'type'        => 'fixed',
                'value'       => 150,
                'description' => 'New Aadhaar Apply Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Aadhaar PVC Card',
                'code'        => 'aadhaar_pvc_card',
                'type'        => 'fixed',
                'value'       => 75,
                'description' => 'PVC Card Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Aadhaar Download',
                'code'        => 'aadhaar_download',
                'type'        => 'fixed',
                'value'       => 20,
                'description' => 'Aadhaar Download Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Aadhaar Status Check',
                'code'        => 'aadhaar_status_check',
                'type'        => 'fixed',
                'value'       => 10,
                'description' => 'Aadhaar Status Check Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            [
                'name'        => 'Aadhaar Verification',
                'code'        => 'aadhaar_verification',
                'type'        => 'fixed',
                'value'       => 10,
                'description' => 'Aadhaar Verification Charge',
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

        ]);
    }
}