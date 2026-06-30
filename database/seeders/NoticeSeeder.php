<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notice;
use Carbon\Carbon;

class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notices = [

            [
                'title' => 'Instant PAN Card Service Started',
                'description' => 'Users can now apply for Instant PAN Card through Aadhaar-based eKYC verification.',
                'publish_date' => Carbon::now()->subDays(3),
                'expiry_date' => Carbon::now()->addDays(15),
                
            ],

            [
                'title' => 'Aadhaar Update Camp',
                'description' => 'Special Aadhaar mobile number and biometric update camp will be conducted this Sunday.',
                'publish_date' => Carbon::now()->subDays(1),
                'expiry_date' => Carbon::now()->addDays(7),
               
            ],

            [
                'title' => 'PAN-Aadhaar Linking Mandatory',
                'description' => 'All users are requested to link PAN with Aadhaar before the government deadline.',
                'publish_date' => Carbon::now(),
                'expiry_date' => Carbon::now()->addDays(30),
               
            ],

            [
                'title' => 'Correction Service Available',
                'description' => 'Name, Date of Birth, Address, and Father Name correction services are now available.',
                'publish_date' => Carbon::now()->subDays(5),
                'expiry_date' => Carbon::now()->addDays(20),
                
            ],

            [
                'title' => 'Download e-PAN Facility',
                'description' => 'Users can now instantly download e-PAN after successful PAN generation.',
                'publish_date' => Carbon::now()->subDays(2),
                'expiry_date' => null,
               
            ],

            [
                'title' => 'New Aadhaar Enrollment',
                'description' => 'New Aadhaar enrollment service for children and adults is now open.',
                'publish_date' => Carbon::now(),
                'expiry_date' => Carbon::now()->addDays(25),
                
            ],

        ];

        foreach ($notices as $notice) {

            Notice::updateOrCreate(

                [
                    'title' => $notice['title']
                ],

                $notice
            );
        }
    }
}