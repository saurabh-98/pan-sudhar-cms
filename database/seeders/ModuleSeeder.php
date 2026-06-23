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

            /*                                                                         |
            | -------------------------------------------------------------------------- |
            | CSC SERVICES                                                               |
            | -------------------------------------------------------------------------- |
            | */                                                                         

            $csc = Module::create([
                'name'       => 'CSC Services',
                'slug'       => 'csc-services',
                'icon'       => 'fa fa-landmark',
                'route_name' => null,
                'parent_id'  => null,
                'sort_order' => 40,
                'status'     => 1,
                ]);

                Module::insert([


            [
                'name'       => 'PM Kisan Registration',
                'slug'       => 'pm-kisan-registration',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 1,
                'status'     => 1,
            ],

            [
                'name'       => 'Ayushman Card',
                'slug'       => 'ayushman-card',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 2,
                'status'     => 1,
            ],

            [
                'name'       => 'Income Certificate',
                'slug'       => 'income-certificate',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 3,
                'status'     => 1,
            ],

            [
                'name'       => 'Residence Certificate',
                'slug'       => 'residence-certificate',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 4,
                'status'     => 1,
            ],

            [
                'name'       => 'Caste Certificate',
                'slug'       => 'caste-certificate',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 5,
                'status'     => 1,
            ],

            [
                'name'       => 'Birth Certificate',
                'slug'       => 'birth-certificate',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 6,
                'status'     => 1,
            ],

            [
                'name'       => 'Death Certificate',
                'slug'       => 'death-certificate',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 7,
                'status'     => 1,
            ],

            [
                'name'       => 'Labour Card',
                'slug'       => 'labour-card',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 8,
                'status'     => 1,
            ],

            [
                'name'       => 'E-Shram Card',
                'slug'       => 'e-shram-card',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 9,
                'status'     => 1,
            ],

            [
                'name'       => 'Ration Card',
                'slug'       => 'ration-card',
                'route_name' => 'retailer.csc.service',
                'parent_id'  => $csc->id,
                'sort_order' => 10,
                'status'     => 1,
            ],

            [
                'name'       => 'CSC Service History',
                'slug'       => 'csc-history',
                'route_name' => 'retailer.csc.history',
                'parent_id'  => $csc->id,
                'sort_order' => 11,
                'status'     => 1,
            ],


            


            ]);


            /*
            |--------------------------------------------------------------------------
            | VOTER ID SERVICES
            |--------------------------------------------------------------------------
            */

            $voterId = Module::create([
                'name'       => 'Voter ID Services',
                'slug'       => 'voter-id-services',
                'icon'       => 'fa fa-vote-yea',
                'route_name' => null,
                'parent_id'  => null,
                'sort_order' => 50,
                'status'     => 1,
            ]);

            Module::insert([

                [
                    'name'       => 'New Voter ID Apply',
                    'slug'       => 'new-voter-id',
                    'route_name' => 'retailer.voter-id.service',
                    'parent_id'  => $voterId->id,
                    'sort_order' => 1,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Voter ID Correction',
                    'slug'       => 'voter-id-correction',
                    'route_name' => 'retailer.voter-id.service',
                    'parent_id'  => $voterId->id,
                    'sort_order' => 2,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Mobile Number Update',
                    'slug'       => 'voter-id-mobile-update',
                    'route_name' => 'retailer.voter-id.service',
                    'parent_id'  => $voterId->id,
                    'sort_order' => 3,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Address Update',
                    'slug'       => 'voter-id-address-update',
                    'route_name' => 'retailer.voter-id.service',
                    'parent_id'  => $voterId->id,
                    'sort_order' => 4,
                    'status'     => 1,
                ],

                [
                    'name'       => 'DOB Update',
                    'slug'       => 'voter-id-dob-update',
                    'route_name' => 'retailer.voter-id.service',
                    'parent_id'  => $voterId->id,
                    'sort_order' => 5,
                    'status'     => 1,
                ],

                [
                    'name'       => 'Voter ID History',
                    'slug'       => 'voter-id-history',
                    'route_name' => 'retailer.voter-id.history',
                    'parent_id'  => $voterId->id,
                    'sort_order' => 6,
                    'status'     => 1,
                ],

            ]);

            /*
        |--------------------------------------------------------------------------
        | BANK ACCOUNT SERVICES
        |--------------------------------------------------------------------------
        */

        $bankAccount = Module::create([
            'name'       => 'Bank Account Services',
            'slug'       => 'bank-account-services',
            'icon'       => 'fa fa-university',
            'route_name' => null,
            'parent_id'  => null,
            'sort_order' => 60,
            'status'     => 1,
        ]);

        Module::insert([

            [
                'name'       => 'New Bank Account Opening',
                'slug'       => 'new-bank-account',
                'route_name' => 'retailer.bank-account.service',
                'parent_id'  => $bankAccount->id,
                'sort_order' => 1,
                'status'     => 1,
            ],

            [
                'name'       => 'Account Closure',
                'slug'       => 'account-closure',
                'route_name' => 'retailer.bank-account.service',
                'parent_id'  => $bankAccount->id,
                'sort_order' => 2,
                'status'     => 1,
            ],

            [
                'name'       => 'Mobile Number Update',
                'slug'       => 'bank-mobile-update',
                'route_name' => 'retailer.bank-account.service',
                'parent_id'  => $bankAccount->id,
                'sort_order' => 3,
                'status'     => 1,
            ],

            [
                'name'       => 'Address Update',
                'slug'       => 'bank-address-update',
                'route_name' => 'retailer.bank-account.service',
                'parent_id'  => $bankAccount->id,
                'sort_order' => 4,
                'status'     => 1,
            ],

            [
                'name'       => 'KYC Update',
                'slug'       => 'kyc-update',
                'route_name' => 'retailer.bank-account.service',
                'parent_id'  => $bankAccount->id,
                'sort_order' => 5,
                'status'     => 1,
            ],

            [
                'name'       => 'Nominee Update',
                'slug'       => 'nominee-update',
                'route_name' => 'retailer.bank-account.service',
                'parent_id'  => $bankAccount->id,
                'sort_order' => 6,
                'status'     => 1,
            ],

            [
                'name'       => 'Bank Account History',
                'slug'       => 'bank-account-history',
                'route_name' => 'retailer.bank-account.history',
                'parent_id'  => $bankAccount->id,
                'sort_order' => 7,
                'status'     => 1,
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | OTHER SERVICES
        |--------------------------------------------------------------------------
        */

        $otherService = Module::create([
            'name'       => 'Other Services',
            'slug'       => 'other-services',
            'icon'       => 'fa fa-briefcase',
            'route_name' => null,
            'parent_id'  => null,
            'sort_order' => 70,
            'status'     => 1,
        ]);

        Module::insert([

            [
                'name'       => 'Raj Patra',
                'slug'       => 'raj-patra',
                'route_name' => 'retailer.other-service.service',
                'parent_id'  => $otherService->id,
                'sort_order' => 1,
                'status'     => 1,
            ],

            [
                'name'       => 'GST Registration / Filing',
                'slug'       => 'gst-registration-filing',
                'route_name' => 'retailer.other-service.service',
                'parent_id'  => $otherService->id,
                'sort_order' => 2,
                'status'     => 1,
            ],

            [
                'name'       => 'Food Licence',
                'slug'       => 'food-licence',
                'route_name' => 'retailer.other-service.service',
                'parent_id'  => $otherService->id,
                'sort_order' => 3,
                'status'     => 1,
            ],

            [
                'name'       => 'ITR Filing / TDS Refund',
                'slug'       => 'itr-filing-tds-refund',
                'route_name' => 'retailer.other-service.service',
                'parent_id'  => $otherService->id,
                'sort_order' => 4,
                'status'     => 1,
            ],

            [
                'name'       => 'DSC Digital Signature',
                'slug'       => 'dsc-digital-signature',
                'route_name' => 'retailer.other-service.service',
                'parent_id'  => $otherService->id,
                'sort_order' => 5,
                'status'     => 1,
            ],

            [
                'name'       => 'MSME Registration',
                'slug'       => 'msme-registration',
                'route_name' => 'retailer.other-service.service',
                'parent_id'  => $otherService->id,
                'sort_order' => 6,
                'status'     => 1,
            ],

            [
                'name'       => 'Import Export Certificate',
                'slug'       => 'import-export-certificate',
                'route_name' => 'retailer.other-service.service',
                'parent_id'  => $otherService->id,
                'sort_order' => 7,
                'status'     => 1,
            ],

            [
                'name'       => 'Rent Agreement',
                'slug'       => 'rent-agreement',
                'route_name' => 'retailer.other-service.service',
                'parent_id'  => $otherService->id,
                'sort_order' => 8,
                'status'     => 1,
            ],

            [
                'name'       => 'Police Verification',
                'slug'       => 'police-verification',
                'route_name' => 'retailer.other-service.service',
                'parent_id'  => $otherService->id,
                'sort_order' => 9,
                'status'     => 1,
            ],

            [
                'name'       => 'Other Service History',
                'slug'       => 'other-service-history',
                'route_name' => 'retailer.other-service.history',
                'parent_id'  => $otherService->id,
                'sort_order' => 10,
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