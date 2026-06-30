<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::truncate();

        $banners = [

            [
                'title' => 'Apply New PAN Card Online',
                'subtitle' => 'Fast, secure and fully digital PAN application platform with instant e-PAN services.',
                'button_text' => 'Apply PAN Card',
                'image' => [
                    'banners/pan-banner-1.jpg'
                ],
            ],

            [
                'title' => 'Correct Everything In PAN Card',
                'subtitle' => 'Update name, date of birth, photo, signature, mobile number and Aadhaar details online.',
                'button_text' => 'PAN Correction',
                'image' => [
                    'banners/pan-banner-2.jpg'
                ],
            ],

            [
                'title' => 'Download Instant e-PAN Card',
                'subtitle' => 'Get your digital e-PAN quickly with secure online verification and instant delivery.',
                'button_text' => 'Download e-PAN',
                'image' => [
                    'banners/pan-banner-3.jpg'
                ],
            ],

            [
                'title' => 'Become PAN Retailer & Earn More',
                'subtitle' => 'Start your PAN service business and earn commission with every successful application.',
                'button_text' => 'Join Retailer',
                'image' => [
                    'banners/pan-banner-4.jpg'
                ],
            ],

            [
                'title' => 'Track PAN Application Status Online',
                'subtitle' => 'Check real-time PAN application status anytime with our secure tracking system.',
                'button_text' => 'Track PAN',
                'image' => [
                    'banners/pan-banner-5.jpg'
                ],
            ],

        ];

        foreach ($banners as $banner) {

            Banner::create([
                'title'       => $banner['title'],
                'subtitle'    => $banner['subtitle'],
                'button_text' => $banner['button_text'],
                'image'       => $banner['image'],
                'type'        => 'hero',
                'is_active'   => true,
            ]);

        }
    }
}