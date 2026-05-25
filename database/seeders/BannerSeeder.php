<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::truncate();

        /*
        |--------------------------------------------------------------------------
        | HERO BANNER 1
        |--------------------------------------------------------------------------
        */

        Banner::create([

            'title' => 'Apply New PAN Card Online',

            'subtitle' => 'Fast, secure and fully digital PAN application platform with instant e-PAN services.',

            'button_text' => 'Apply PAN Card',


            'image' => json_encode([

                'banners/pan-banner-1.jpg'

            ]),

            'type' => 'hero',

            'is_active' => 1

        ]);

        /*
        |--------------------------------------------------------------------------
        | HERO BANNER 2
        |--------------------------------------------------------------------------
        */

        Banner::create([

            'title' => 'Correct Everything In PAN Card',

            'subtitle' => 'Update name, date of birth, photo, signature, mobile number and Aadhaar details online.',

            'button_text' => 'PAN Correction',


            'image' => json_encode([

                'banners/pan-banner-2.jpg'

            ]),

            'type' => 'hero',

            'is_active' => 1

        ]);

        /*
        |--------------------------------------------------------------------------
        | HERO BANNER 3
        |--------------------------------------------------------------------------
        */

        Banner::create([

            'title' => 'Download Instant e-PAN Card',

            'subtitle' => 'Get your digital e-PAN quickly with secure online verification and instant delivery.',

            'button_text' => 'Download e-PAN',

            

            'image' => json_encode([

                'banners/pan-banner-3.jpg'

            ]),

            'type' => 'hero',

            'is_active' => 1

        ]);

        /*
        |--------------------------------------------------------------------------
        | HERO BANNER 4
        |--------------------------------------------------------------------------
        */

        Banner::create([

            'title' => 'Become PAN Retailer & Earn More',

            'subtitle' => 'Start your PAN service business and earn commission with every successful application.',

            'button_text' => 'Join Retailer',

           

            'image' => json_encode([

                'banners/pan-banner-4.jpg'

            ]),

            'type' => 'hero',

            'is_active' => 1

        ]);

        /*
        |--------------------------------------------------------------------------
        | HERO BANNER 5
        |--------------------------------------------------------------------------
        */

        Banner::create([

            'title' => 'Track PAN Application Status Online',

            'subtitle' => 'Check real-time PAN application status anytime with our secure tracking system.',

            'button_text' => 'Track PAN',

           

            'image' => json_encode([

                'banners/pan-banner-5.jpg'

            ]),

            'type' => 'hero',

            'is_active' => 1

        ]);
    }
}