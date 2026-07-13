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

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            Module::updateOrCreate(
                ['slug' => 'dashboard'],
                [
                    'name'       => 'Dashboard',
                    'icon'       => 'fa-solid fa-house',
                    'route_name' => 'retailer.dashboard',
                    'parent_id'  => null,
                    'sort_order' => 1,
                    'status'     => 1,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */

            Module::updateOrCreate(
                ['slug' => 'profile'],
                [
                    'name'       => 'Profile',
                    'icon'       => 'fa-solid fa-user-circle',
                    'route_name' => 'retailer.profile',
                    'parent_id'  => null,
                    'sort_order' => 2,
                    'status'     => 1,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | SUPPORT CHAT
            |--------------------------------------------------------------------------
            */

            Module::updateOrCreate(
                ['slug' => 'support-chat'],
                [
                    'name'       => 'Support Chat',
                    'icon'       => 'fa-solid fa-headset',
                    'route_name' => 'retailer.chat.index',
                    'parent_id'  => null,
                    'sort_order' => 3,
                    'status'     => 1,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | WALLET
            |--------------------------------------------------------------------------
            */

            $wallet = Module::updateOrCreate(
                ['slug' => 'wallet'],
                [
                    'name'       => 'Wallet',
                    'icon'       => 'fa-solid fa-wallet',
                    'route_name' => null,
                    'parent_id'  => null,
                    'sort_order' => 4,
                    'status'     => 1,
                ]
            );

            Module::updateOrCreate(
                ['slug' => 'wallet-recharge'],
                [
                    'name'       => 'Recharge Wallet',
                    'icon'       => 'fa-solid fa-money-bill-transfer',
                    'route_name' => 'retailer.wallet.recharge',
                    'parent_id'  => $wallet->id,
                    'sort_order' => 1,
                    'status'     => 1,
                ]
            );

            Module::updateOrCreate(
                ['slug' => 'wallet-recharge-history'],
                [
                    'name'       => 'Recharge History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.wallet.recharge-history',
                    'parent_id'  => $wallet->id,
                    'sort_order' => 2,
                    'status'     => 1,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | PAN SERVICES
            |--------------------------------------------------------------------------
            */

            $pan = Module::updateOrCreate(
                ['slug' => 'pan-services'],
                [
                    'name'       => 'PAN Services',
                    'icon'       => 'fa-solid fa-id-card',
                    'route_name' => null,
                    'parent_id'  => null,
                    'sort_order' => 10,
                    'status'     => 1,
                ]
            );

            $panChildren = [
                [
                    'slug'       => 'new-pan-apply',
                    'name'       => 'New PAN Apply',
                    'icon'       => 'fa-solid fa-file-circle-plus',
                    'route_name' => 'retailer.pan.apply',
                    'sort_order' => 1,
                ],
                [
                    'slug'       => 'pan-history',
                    'name'       => 'PAN History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.pan.history',
                    'sort_order' => 2,
                ],
                [
                    'slug'       => 'pan-correction',
                    'name'       => 'PAN Correction',
                    'icon'       => 'fa-solid fa-pen-to-square',
                    'route_name' => 'retailer.pan-correction.apply',
                    'sort_order' => 3,
                ],
                [
                    'slug'       => 'pan-correction-history',
                    'name'       => 'PAN Correction History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.pan-correction.history',
                    'sort_order' => 4,
                ],
                [
                    'slug'       => 'pan-without-docs',
                    'name'       => 'PAN Apply Without Docs',
                    'icon'       => 'fa-solid fa-file-circle-question',
                    'route_name' => 'retailer.pan-apply-without-document.apply',
                    'sort_order' => 5,
                ],
                [
                    'slug'       => 'pan-without-docs-history',
                    'name'       => 'PAN Without Docs History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.pan-apply-without-document.history',
                    'sort_order' => 6,
                ],
                [
                    'slug'       => 'pan-training',
                    'name'       => 'PAN Training',
                    'icon'       => 'fa-solid fa-chalkboard-user',
                    'route_name' => 'retailer.pan.training',
                    'sort_order' => 7,
                ],
                [
                    'slug'       => 'pan-find',
                    'name'       => 'PAN Find/Aadhar To PAN',
                    'icon'       => 'fa-solid fa-magnifying-glass',
                    'route_name' => 'retailer.pan-find.apply',
                    'sort_order' => 8,
                ],
                [
                    'slug'       => 'pan-find-history',
                    'name'       => 'PAN Find/Aadhar To PAN History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.pan-find.history',
                    'sort_order' => 9,
                ],
            ];

            foreach ($panChildren as $child) {
                Module::updateOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'name'       => $child['name'],
                        'icon'       => $child['icon'],
                        'route_name' => $child['route_name'],
                        'parent_id'  => $pan->id,
                        'sort_order' => $child['sort_order'],
                        'status'     => 1,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | ITR / FINANCIAL SERVICES
            |--------------------------------------------------------------------------
            */

            $itr = Module::updateOrCreate(
                ['slug' => 'itr-services'],
                [
                    'name'       => 'Financial Services',
                    'icon'       => 'fa-solid fa-file-invoice',
                    'route_name' => null,
                    'parent_id'  => null,
                    'sort_order' => 20,
                    'status'     => 1,
                ]
            );

            $itrChildren = [
                [
                    'slug'       => 'file-itr',
                    'name'       => 'File ITR (Salary Income)',
                    'icon'       => 'fa-solid fa-file-invoice-dollar',
                    'route_name' => 'retailer.itr.index',
                    'sort_order' => 1,
                ],
                [
                    'slug'       => 'itr-file-tds-refund',
                    'name'       => 'ITR Filing & TDS Refund',
                    'icon'       => 'fa-solid fa-hand-holding-dollar',
                    'route_name' => 'retailer.itr.index',
                    'sort_order' => 2,
                ],
                [
                    'slug'       => 'gst-registration-filing',
                    'name'       => 'GST Registration / Filing',
                    'icon'       => 'fa-solid fa-receipt',
                    'route_name' => 'retailer.itr.index',
                    'sort_order' => 3,
                ],
                [
                    'slug'       => 'dsc-digital-signature',
                    'name'       => 'DSC Digital Signature',
                    'icon'       => 'fa-solid fa-signature',
                    'route_name' => 'retailer.itr.index',
                    'sort_order' => 4,
                ],
                [
                    'slug'       => 'msme-registration',
                    'name'       => 'MSME Registration',
                    'icon'       => 'fa-solid fa-industry',
                    'route_name' => 'retailer.itr.index',
                    'sort_order' => 5,
                ],
                [
                    'slug'       => 'itr-history',
                    'name'       => 'ITR History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.itr.history',
                    'sort_order' => 6,
                ],
            ];

            foreach ($itrChildren as $child) {
                Module::updateOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'name'       => $child['name'],
                        'icon'       => $child['icon'],
                        'route_name' => $child['route_name'],
                        'parent_id'  => $itr->id,
                        'sort_order' => $child['sort_order'],
                        'status'     => 1,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | AADHAAR SERVICES
            |--------------------------------------------------------------------------
            */

            $aadhaar = Module::updateOrCreate(
                ['slug' => 'aadhaar-services'],
                [
                    'name'       => 'Aadhaar Services',
                    'icon'       => 'fa-solid fa-address-card',
                    'route_name' => null,
                    'parent_id'  => null,
                    'sort_order' => 30,
                    'status'     => 1,
                ]
            );

            $aadhaarChildren = [
                [
                    'slug'       => 'mobile-number-update',
                    'name'       => 'Mobile Number Update',
                    'icon'       => 'fa-solid fa-mobile-screen',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 1,
                ],
                [
                    'slug'       => 'name-correction',
                    'name'       => 'Name Correction',
                    'icon'       => 'fa-solid fa-signature',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 2,
                ],
                [
                    'slug'       => 'dob-correction',
                    'name'       => 'DOB Correction',
                    'icon'       => 'fa-solid fa-calendar-days',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 3,
                ],
                [
                    'slug'       => 'address-update',
                    'name'       => 'Address Update',
                    'icon'       => 'fa-solid fa-location-dot',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 4,
                ],
                [
                    'slug'       => 'father-name-update',
                    'name'       => 'Father Name Update',
                    'icon'       => 'fa-solid fa-person',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 5,
                ],
                [
                    'slug'       => 'husband-name-update',
                    'name'       => 'Husband Name Update',
                    'icon'       => 'fa-solid fa-people-arrows',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 6,
                ],
                [
                    'slug'       => 'gender-update',
                    'name'       => 'Gender Update',
                    'icon'       => 'fa-solid fa-venus-mars',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 7,
                ],
                [
                    'slug'       => 'email-update',
                    'name'       => 'Email/Misc Update',
                    'icon'       => 'fa-solid fa-envelope',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 8,
                ],
                [
                    'slug'       => 'biometric-appointment',
                    'name'       => 'Biometric Appointment',
                    'icon'       => 'fa-solid fa-fingerprint',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 9,
                ],
                [
                    'slug'       => 'child-aadhaar-enrollment',
                    'name'       => 'Child Aadhaar Enrollment',
                    'icon'       => 'fa-solid fa-child',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 10,
                ],
                [
                    'slug'       => 'new-aadhaar-apply',
                    'name'       => 'New Aadhaar Apply',
                    'icon'       => 'fa-solid fa-id-badge',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 11,
                ],
                [
                    'slug'       => 'aadhaar-pvc-card',
                    'name'       => 'Aadhaar PVC Card',
                    'icon'       => 'fa-solid fa-credit-card',
                    'route_name' => 'retailer.aadhaar.service',
                    'sort_order' => 12,
                ],
                [
                    'slug'       => 'aadhaar-history',
                    'name'       => 'Aadhaar Service History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.aadhaar.history',
                    'sort_order' => 13,
                ],
            ];

            foreach ($aadhaarChildren as $child) {
                Module::updateOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'name'       => $child['name'],
                        'icon'       => $child['icon'],
                        'route_name' => $child['route_name'],
                        'parent_id'  => $aadhaar->id,
                        'sort_order' => $child['sort_order'],
                        'status'     => 1,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CSC SERVICES
            |--------------------------------------------------------------------------
            */

            $csc = Module::updateOrCreate(
                ['slug' => 'csc-services'],
                [
                    'name'       => 'CSC Services',
                    'icon'       => 'fa-solid fa-landmark',
                    'route_name' => null,
                    'parent_id'  => null,
                    'sort_order' => 40,
                    'status'     => 1,
                ]
            );

            $cscChildren = [
                [
                    'slug'       => 'pm-kisan-registration',
                    'name'       => 'PM Kisan Registration',
                    'icon'       => 'fa-solid fa-tractor',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 1,
                ],
                [
                    'slug'       => 'ayushman-card',
                    'name'       => 'Ayushman Card',
                    'icon'       => 'fa-solid fa-heart-pulse',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 2,
                ],
                [
                    'slug'       => 'income-certificate',
                    'name'       => 'Income Certificate',
                    'icon'       => 'fa-solid fa-sack-dollar',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 3,
                ],
                [
                    'slug'       => 'domicile-niwas-certificate',
                    'name'       => 'Domicile/Niwas Certificate',
                    'icon'       => 'fa-solid fa-house-chimney',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 4,
                ],
                [
                    'slug'       => 'caste-certificate',
                    'name'       => 'Caste Certificate',
                    'icon'       => 'fa-solid fa-people-group',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 5,
                ],
                [
                    'slug'       => 'birth-certificate',
                    'name'       => 'Birth Certificate',
                    'icon'       => 'fa-solid fa-baby',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 6,
                ],
                [
                    'slug'       => 'death-certificate',
                    'name'       => 'Death Certificate',
                    'icon'       => 'fa-solid fa-cross',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 7,
                ],
                [
                    'slug'       => 'labour-card',
                    'name'       => 'Labour Card',
                    'icon'       => 'fa-solid fa-helmet-safety',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 8,
                ],
                [
                    'slug'       => 'e-shram-card',
                    'name'       => 'E-Shram Card',
                    'icon'       => 'fa-solid fa-id-card-clip',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 9,
                ],
                [
                    'slug'       => 'ration-card',
                    'name'       => 'Ration Card',
                    'icon'       => 'fa-solid fa-wheat-awn',
                    'route_name' => 'retailer.csc.service',
                    'sort_order' => 10,
                ],
                [
                    'slug'       => 'csc-history',
                    'name'       => 'CSC Service History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.csc.history',
                    'sort_order' => 11,
                ],
            ];

            foreach ($cscChildren as $child) {
                Module::updateOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'name'       => $child['name'],
                        'icon'       => $child['icon'],
                        'route_name' => $child['route_name'],
                        'parent_id'  => $csc->id,
                        'sort_order' => $child['sort_order'],
                        'status'     => 1,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | VOTER ID SERVICES
            |--------------------------------------------------------------------------
            */

            $voterId = Module::updateOrCreate(
                ['slug' => 'voter-id-services'],
                [
                    'name'       => 'Voter ID Services',
                    'icon'       => 'fa-solid fa-vote-yea',
                    'route_name' => null,
                    'parent_id'  => null,
                    'sort_order' => 50,
                    'status'     => 1,
                ]
            );

            $voterIdChildren = [
                [
                    'slug'       => 'new-voter-id',
                    'name'       => 'New Voter ID Apply',
                    'icon'       => 'fa-solid fa-square-plus',
                    'route_name' => 'retailer.voter-id.service',
                    'sort_order' => 1,
                ],
                [
                    'slug'       => 'voter-id-correction',
                    'name'       => 'Voter ID Correction',
                    'icon'       => 'fa-solid fa-pen-to-square',
                    'route_name' => 'retailer.voter-id.service',
                    'sort_order' => 2,
                ],
                [
                    'slug'       => 'voter-id-mobile-update',
                    'name'       => 'Mobile Number Update',
                    'icon'       => 'fa-solid fa-mobile-screen',
                    'route_name' => 'retailer.voter-id.service',
                    'sort_order' => 3,
                ],
                [
                    'slug'       => 'voter-id-address-update',
                    'name'       => 'Address Update',
                    'icon'       => 'fa-solid fa-location-dot',
                    'route_name' => 'retailer.voter-id.service',
                    'sort_order' => 4,
                ],
                [
                    'slug'       => 'voter-id-dob-update',
                    'name'       => 'DOB Update',
                    'icon'       => 'fa-solid fa-calendar-days',
                    'route_name' => 'retailer.voter-id.service',
                    'sort_order' => 5,
                ],
                [
                    'slug'       => 'voter-id-history',
                    'name'       => 'Voter ID History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.voter-id.history',
                    'sort_order' => 6,
                ],
            ];

            foreach ($voterIdChildren as $child) {
                Module::updateOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'name'       => $child['name'],
                        'icon'       => $child['icon'],
                        'route_name' => $child['route_name'],
                        'parent_id'  => $voterId->id,
                        'sort_order' => $child['sort_order'],
                        'status'     => 1,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | BANK ACCOUNT SERVICES
            |--------------------------------------------------------------------------
            */

            $bankAccount = Module::updateOrCreate(
                ['slug' => 'bank-account-services'],
                [
                    'name'       => 'Bank Account Services',
                    'icon'       => 'fa-solid fa-university',
                    'route_name' => null,
                    'parent_id'  => null,
                    'sort_order' => 60,
                    'status'     => 1,
                ]
            );

            $bankAccountChildren = [
                [
                    'slug'       => 'airtel-bank-account',
                    'name'       => 'Airtel Bank Account Opening',
                    'icon'       => 'fa-solid fa-piggy-bank',
                    'route_name' => 'retailer.bank-account.service',
                    'sort_order' => 1,
                ],
                [
                    'slug'       => 'indian-bank',
                    'name'       => 'Indian Bank',
                    'icon'       => 'fa-solid fa-building-columns',
                    'route_name' => 'retailer.bank-account.service',
                    'sort_order' => 2,
                ],
                [
                    'slug'       => 'indian-overseas-bank',
                    'name'       => 'Indian Overseas Bank',
                    'icon'       => 'fa-solid fa-building-columns',
                    'route_name' => 'retailer.bank-account.service',
                    'sort_order' => 3,
                ],
                [
                    'slug'       => 'nsdl-payment-bank',
                    'name'       => 'NSDL Payment Bank',
                    'icon'       => 'fa-solid fa-building-columns',
                    'route_name' => 'retailer.bank-account.service',
                    'sort_order' => 4,
                ],
                [
                    'slug'       => 'jio-payment-bank',
                    'name'       => 'Jio Payment Bank',
                    'icon'       => 'fa-solid fa-building-columns',
                    'route_name' => 'retailer.bank-account.service',
                    'sort_order' => 5,
                ],
                [
                    'slug'       => 'bank-of-baroda',
                    'name'       => 'Bank of Baroda',
                    'icon'       => 'fa-solid fa-building-columns',
                    'route_name' => 'retailer.bank-account.service',
                    'sort_order' => 6,
                ],
                [
                    'slug'       => 'kotak-bank-account',
                    'name'       => 'Kotak Bank Account',
                    'icon'       => 'fa-solid fa-building-columns',
                    'route_name' => 'retailer.bank-account.service',
                    'sort_order' => 7,
                ],
                [
                    'slug'       => 'sbi-pnb-bank-account',
                    'name'       => 'SBI/PNB Bank Account',
                    'icon'       => 'fa-solid fa-building-columns',
                    'route_name' => 'retailer.bank-account.service',
                    'sort_order' => 8,
                ],
                [
                    'slug'       => 'bank-account-history',
                    'name'       => 'Bank Account History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.bank-account.history',
                    'sort_order' => 9,
                ],
            ];

            foreach ($bankAccountChildren as $child) {
                Module::updateOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'name'       => $child['name'],
                        'icon'       => $child['icon'],
                        'route_name' => $child['route_name'],
                        'parent_id'  => $bankAccount->id,
                        'sort_order' => $child['sort_order'],
                        'status'     => 1,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | OTHER SERVICES
            |--------------------------------------------------------------------------
            */

            $otherService = Module::updateOrCreate(
                ['slug' => 'other-services'],
                [
                    'name'       => 'Other Services',
                    'icon'       => 'fa-solid fa-briefcase',
                    'route_name' => null,
                    'parent_id'  => null,
                    'sort_order' => 70,
                    'status'     => 1,
                ]
            );

            $otherServiceChildren = [
                [
                    'slug'       => 'raj-patra',
                    'name'       => 'Raj Patra',
                    'icon'       => 'fa-solid fa-newspaper',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 1,
                ],
                [
                    'slug'       => 'food-licence',
                    'name'       => 'Food Licence',
                    'icon'       => 'fa-solid fa-utensils',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 3,
                ],
                [
                    'slug'       => 'npci-aadhaar-seeding',
                    'name'       => 'NPCI Aadhaar Seeding',
                    'icon'       => 'fa-solid fa-link',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 4,
                ],
                [
                    'slug'       => 'import-export-certificate',
                    'name'       => 'Import Export Certificate(IEC)',
                    'icon'       => 'fa-solid fa-ship',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 7,
                ],
                [
                    'slug'       => 'rent-agreement',
                    'name'       => 'Rent Agreement',
                    'icon'       => 'fa-solid fa-file-contract',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 8,
                ],
                [
                    'slug'       => 'police-verification',
                    'name'       => 'Police Verification',
                    'icon'       => 'fa-solid fa-shield-halved',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 9,
                ],
                [
                    'slug'       => 'driving-learning-license',
                    'name'       => 'Driving Learning License',
                    'icon'       => 'fa-solid fa-car',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 10,
                ],
                [
                    'slug'       => 'vehicle-chalan-payment',
                    'name'       => 'Vehicle Chalan Payment',
                    'icon'       => 'fa-solid fa-money-check-dollar',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 11,
                ],
                [
                    'slug'       => 'rto-service',
                    'name'       => 'RTO Sevice',
                    'icon'       => 'fa-solid fa-car-side',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 12,
                ],
                [
                    'slug'       => 'passport-service',
                    'name'       => 'Passport Service',
                    'icon'       => 'fa-solid fa-passport',
                    'route_name' => 'retailer.other-service.service',
                    'sort_order' => 13,
                ],
                [
                    'slug'       => 'other-service-history',
                    'name'       => 'Other Service History',
                    'icon'       => 'fa-solid fa-clock-rotate-left',
                    'route_name' => 'retailer.other-service.history',
                    'sort_order' => 14,
                ],
            ];

            foreach ($otherServiceChildren as $child) {
                Module::updateOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'name'       => $child['name'],
                        'icon'       => $child['icon'],
                        'route_name' => $child['route_name'],
                        'parent_id'  => $otherService->id,
                        'sort_order' => $child['sort_order'],
                        'status'     => 1,
                    ]
                );
            }

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }
}
