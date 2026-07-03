<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class UpdateServiceIconsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Updates the `icon` column on existing Module rows by matching
     * on their display `name`. Safe to re-run — only touches rows
     * whose name matches one of the keys below.
     */
    public function run(): void
    {
        $icons = [

            /*
            |----------------------------------------------------------------
            | PAN SERVICES
            |----------------------------------------------------------------
            */

            'New PAN Apply'          => 'fa-solid fa-id-card',
            'PAN History'            => 'fa-solid fa-clock-rotate-left',
            'PAN Correction'         => 'fa-solid fa-pen-to-square',
            'PAN Correction History' => 'fa-solid fa-file-pen',
            'PAN Apply Without Docs' => 'fa-solid fa-id-card-clip',
            'PAN Find/Aadhar To PAN' => 'fa-solid fa-magnifying-glass',

            /*
            |----------------------------------------------------------------
            | ITR SERVICES
            |----------------------------------------------------------------
            */

            'File ITR (Salary Income)' => 'fa-solid fa-file-invoice-dollar',
            'ITR Filing & TDS Refund'  => 'fa-solid fa-hand-holding-dollar',
            'ITR History'              => 'fa-solid fa-clock-rotate-left',

            /*
            |----------------------------------------------------------------
            | AADHAAR SERVICES
            |----------------------------------------------------------------
            */

            'Mobile Number Update'    => 'fa-solid fa-mobile-screen',
            'Name Correction'         => 'fa-solid fa-user-pen',
            'DOB Correction'          => 'fa-solid fa-calendar-days',
            'Address Update'          => 'fa-solid fa-location-dot',
            'Father Name Update'      => 'fa-solid fa-user-tie',
            'Husband Name Update'     => 'fa-solid fa-ring',
            'Gender Update'           => 'fa-solid fa-venus-mars',
            'Email/Misc Update'       => 'fa-solid fa-envelope',
            'Biometric Appointment'   => 'fa-solid fa-fingerprint',
            'Child Aadhaar Enrollment'=> 'fa-solid fa-baby',
            'New Aadhaar Apply'       => 'fa-solid fa-id-card',
            'Aadhaar PVC Card'        => 'fa-solid fa-credit-card',

        ];

        foreach ($icons as $name => $icon) {

            Module::where('name', $name)->update([
                'icon' => $icon,
            ]);

        }

        $this->command?->info(
            'Updated icons for ' . count($icons) . ' service modules.'
        );
    }
}