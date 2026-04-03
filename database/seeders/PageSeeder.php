<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [

            [
                'title' => 'About Us',
                'slug'  => 'about-us',
                'content' => '<h2>About Our Restaurant</h2><p>We serve delicious food with love ❤️</p>',
                'status' => 1
            ],

            [
                'title' => 'Contact Us',
                'slug'  => 'contact-us',
                'content' => '<h2>Contact Us</h2><p>Email: support@foodies.com</p>',
                'status' => 1
            ],

            [
                'title' => 'Privacy Policy',
                'slug'  => 'privacy-policy',
                'content' => '<h2>Privacy Policy</h2><p>Your data is safe with us 🔒</p>',
                'status' => 1
            ],

            [
                'title' => 'Terms & Conditions',
                'slug'  => 'terms-conditions',
                'content' => '<h2>Terms & Conditions</h2><p>Please read carefully before using our service.</p>',
                'status' => 1
            ],

            [
                'title' => 'Refund Policy',
                'slug'  => 'refund-policy',
                'content' => '<h2>Refund Policy</h2><p>Refunds are processed within 5-7 working days.</p>',
                'status' => 1
            ],

        ];

        foreach ($pages as $page) {

            Page::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title'   => $page['title'],
                    'content' => $page['content'],
                    'status'  => $page['status']
                ]
            );

        }
    }
}