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

        ]);
    }
}