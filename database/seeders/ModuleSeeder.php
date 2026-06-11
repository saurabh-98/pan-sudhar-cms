<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::table('retailer_module_access')->delete();

            DB::table('modules')->delete();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            Module::create([
                'name'       => 'Dashboard',
                'slug'       => 'dashboard',
                'icon'       => 'fa fa-home',
                'route_name' => 'retailer.dashboard',
                'parent_id'  => null,
                'sort_order' => 1,
                'status'     => 1,
            ]);

            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */

            Module::create([
                'name'       => 'Profile',
                'slug'       => 'profile',
                'icon'       => 'fa fa-user',
                'route_name' => 'retailer.profile',
                'parent_id'  => null,
                'sort_order' => 2,
                'status'     => 1,
            ]);

          
            /*
            |--------------------------------------------------------------------------
            | WALLET
            |--------------------------------------------------------------------------
            */

            Module::create([
                'name'       => 'Wallet History',
                'slug'       => 'wallet-history',
                'icon'       => 'fa fa-wallet',
                'route_name' => 'retailer.wallet.history',
                'parent_id'  => null,
                'sort_order' => 4,
                'status'     => 1,
            ]);

            /*
            |--------------------------------------------------------------------------
            | PAN SERVICES
            |--------------------------------------------------------------------------
            */

            $pan = Module::create([
                'name'       => 'PAN Services',
                'slug'       => 'pan-services',
                'icon'       => 'fa fa-id-card',
                'route_name' => null,
                'parent_id'  => null,
                'sort_order' => 10,
                'status'     => 1,
            ]);

            Module::insert([

                [
                    'name'       => 'New PAN Apply',
                    'slug'       => 'new-pan-apply',
                    'route_name' => 'retailer.pan.apply',
                    'parent_id'  => $pan->id,
                    'sort_order' => 1,
                    'status'     => 1,
                ],

                [
                    'name'       => 'PAN History',
                    'slug'       => 'pan-history',
                    'route_name' => 'retailer.pan.history',
                    'parent_id'  => $pan->id,
                    'sort_order' => 2,
                    'status'     => 1,
                ],

                [
                    'name'       => 'PAN Correction',
                    'slug'       => 'pan-correction',
                    'route_name' => 'retailer.pan-correction.apply',
                    'parent_id'  => $pan->id,
                    'sort_order' => 3,
                    'status'     => 1,
                ],

                [
                    'name'       => 'PAN Correction History',
                    'slug'       => 'pan-correction-history',
                    'route_name' => 'retailer.pan-correction.history',
                    'parent_id'  => $pan->id,
                    'sort_order' => 4,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Company PAN',
                    'slug'       => 'company-pan',
                    'route_name' => 'retailer.pan.company',
                    'parent_id'  => $pan->id,
                    'sort_order' => 4,
                    'status'     => 1,
                ],

                [
                    'name'       => 'PAN Training',
                    'slug'       => 'pan-training',
                    'route_name' => 'retailer.pan.training',
                    'parent_id'  => $pan->id,
                    'sort_order' => 5,
                    'status'     => 1,
                ],

                [
                    'name'       => 'PAN Find',
                    'slug'       => 'pan-find',
                    'route_name' => 'retailer.pan.find',
                    'parent_id'  => $pan->id,
                    'sort_order' => 6,
                    'status'     => 1,
                ],

                [
                    'name'       => 'PAN Verify',
                    'slug'       => 'pan-verify',
                    'route_name' => 'retailer.pan.verify',
                    'parent_id'  => $pan->id,
                    'sort_order' => 7,
                    'status'     => 1,
                ],

            ]);

            /*
            |--------------------------------------------------------------------------
            | ITR SERVICES
            |--------------------------------------------------------------------------
            */

            $itr = Module::create([
                'name'       => 'ITR Services',
                'slug'       => 'itr-services',
                'icon'       => 'fa fa-file-invoice',
                'route_name' => null,
                'parent_id'  => null,
                'sort_order' => 20,
                'status'     => 1,
            ]);

            Module::insert([

                [
                    'name'       => 'File ITR',
                    'slug'       => 'file-itr',
                    'route_name' => 'retailer.itr.index',
                    'parent_id'  => $itr->id,
                    'sort_order' => 1,
                    'status'     => 1,
                ],

                [
                    'name'       => 'ITR History',
                    'slug'       => 'itr-history',
                    'route_name' => 'retailer.itr.history',
                    'parent_id'  => $itr->id,
                    'sort_order' => 2,
                    'status'     => 1,
                ],

            ]);


        /*
            |--------------------------------------------------------------------------
            | AADHAAR SERVICES
            |--------------------------------------------------------------------------
            */

            $aadhaar = Module::create([
                'name'       => 'Aadhaar Services',
                'slug'       => 'aadhaar-services',
                'icon'       => 'fa fa-address-card',
                'route_name' => null,
                'parent_id'  => null,
                'sort_order' => 30,
                'status'     => 1,
            ]);

            Module::insert([

                [
                    'name'       => 'Mobile Number Update',
                    'slug'       => 'mobile-number-update',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 1,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Name Correction',
                    'slug'       => 'name-correction',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 2,
                    'status'     => 1,
                ],

                [
                    'name'       => 'DOB Correction',
                    'slug'       => 'dob-correction',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 3,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Address Update',
                    'slug'       => 'address-update',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 4,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Father Name Update',
                    'slug'       => 'father-name-update',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 5,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Husband Name Update',
                    'slug'       => 'husband-name-update',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 6,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Gender Update',
                    'slug'       => 'gender-update',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 7,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Email Update',
                    'slug'       => 'email-update',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 8,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Biometric Appointment',
                    'slug'       => 'biometric-appointment',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 9,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Child Aadhaar Enrollment',
                    'slug'       => 'child-aadhaar-enrollment',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 10,
                    'status'     => 1,
                ],

                [
                    'name'       => 'New Aadhaar Apply',
                    'slug'       => 'new-aadhaar-apply',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 11,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Aadhaar PVC Card',
                    'slug'       => 'aadhaar-pvc-card',
                    'route_name' => 'retailer.aadhaar.service',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 12,
                    'status'     => 1,
                ],


                [
                    'name'       => 'Aadhaar Service History',
                    'slug'       => 'aadhaar-history',
                    'route_name' => 'retailer.aadhaar.history',
                    'parent_id'  => $aadhaar->id,
                    'sort_order' => 16,
                    'status'     => 1,
                ],

            ]);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }
}