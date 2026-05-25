<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FooterLink;
use App\Models\Setting;
use App\Models\SocialLink;

class FooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* =====================================================
           FOOTER LINKS
        ===================================================== */

        $links = [

            /* ================= QUICK LINKS ================= */

            [
                'section' => 'quick_links',
                'name' => 'Home',
                'url' => '/',
                'sort_order' => 1
            ],

            [
                'section' => 'quick_links',
                'name' => 'Apply PAN Card',
                'url' => '/apply-pan-card',
                'sort_order' => 2
            ],

            [
                'section' => 'quick_links',
                'name' => 'PAN Correction',
                'url' => '/pan-correction',
                'sort_order' => 3
            ],

            [
                'section' => 'quick_links',
                'name' => 'Aadhaar Services',
                'url' => '/aadhaar-services',
                'sort_order' => 4
            ],

            [
                'section' => 'quick_links',
                'name' => 'Track Application',
                'url' => '/track-application',
                'sort_order' => 5
            ],

            /* ================= SERVICES ================= */

            [
                'section' => 'services',
                'name' => 'Instant e-PAN',
                'url' => '/instant-epan',
                'sort_order' => 1
            ],

            [
                'section' => 'services',
                'name' => 'PAN-Aadhaar Linking',
                'url' => '/pan-aadhaar-linking',
                'sort_order' => 2
            ],

            [
                'section' => 'services',
                'name' => 'Aadhaar Update',
                'url' => '/aadhaar-update',
                'sort_order' => 3
            ],

            [
                'section' => 'services',
                'name' => 'Mobile Number Update',
                'url' => '/mobile-update',
                'sort_order' => 4
            ],

            [
                'section' => 'services',
                'name' => 'Document Verification',
                'url' => '/document-verification',
                'sort_order' => 5
            ],

            /* ================= SUPPORT ================= */

            [
                'section' => 'support',
                'name' => 'Help Center',
                'url' => '/help-center',
                'sort_order' => 1
            ],

            [
                'section' => 'support',
                'name' => 'FAQs',
                'url' => '/faqs',
                'sort_order' => 2
            ],

            [
                'section' => 'support',
                'name' => 'Contact Us',
                'url' => '/contact-us',
                'sort_order' => 3
            ],

            [
                'section' => 'support',
                'name' => 'Privacy Policy',
                'url' => '/privacy-policy',
                'sort_order' => 4
            ],

            [
                'section' => 'support',
                'name' => 'Terms & Conditions',
                'url' => '/terms-conditions',
                'sort_order' => 5
            ],

            /* ================= COMPANY ================= */

            [
                'section' => 'company',
                'name' => 'About Us',
                'url' => '/about-us',
                'sort_order' => 1
            ],

            [
                'section' => 'company',
                'name' => 'Our Services',
                'url' => '/services',
                'sort_order' => 2
            ],

            [
                'section' => 'company',
                'name' => 'Latest Notices',
                'url' => '/notices',
                'sort_order' => 3
            ],

            [
                'section' => 'company',
                'name' => 'Careers',
                'url' => '/careers',
                'sort_order' => 4
            ],

            [
                'section' => 'company',
                'name' => 'Feedback',
                'url' => '/feedback',
                'sort_order' => 5
            ],

        ];

        foreach ($links as $link) {

            FooterLink::updateOrCreate(

                [
                    'section' => $link['section'],
                    'name'    => $link['name']
                ],

                $link
            );
        }

        /* =====================================================
           SETTINGS
        ===================================================== */

        Setting::updateOrCreate(

            ['key' => 'footer_text'],

            [
                'value' => '© ' . date('Y') . ' PAN & Aadhaar Suvidha Kendra. All Rights Reserved.'
            ]
        );

        Setting::updateOrCreate(

            ['key' => 'footer_tagline'],

            [
                'value' => 'Fast, Secure & Trusted PAN and Aadhaar Services for Everyone.'
            ]
        );

        Setting::updateOrCreate(

            ['key' => 'footer_address'],

            [
                'value' => 'Mithapur, Patna, Bihar - India'
            ]
        );

        Setting::updateOrCreate(

            ['key' => 'footer_phone'],

            [
                'value' => '+91 9876543210'
            ]
        );

        Setting::updateOrCreate(

            ['key' => 'footer_email'],

            [
                'value' => 'support@panaadhaarsuvidha.com'
            ]
        );

        /* =====================================================
           SOCIAL LINKS
        ===================================================== */

        $socials = [

            [
                'icon' => 'fa-facebook-f',
                'url'  => 'https://facebook.com/panaadhaarsuvidha'
            ],

            [
                'icon' => 'fa-instagram',
                'url'  => 'https://instagram.com/panaadhaarsuvidha'
            ],

            [
                'icon' => 'fa-twitter',
                'url'  => 'https://twitter.com/panaadhaarhelp'
            ],

            [
                'icon' => 'fa-youtube',
                'url'  => 'https://youtube.com/@panaadhaarsuvidha'
            ],

            [
                'icon' => 'fa-whatsapp',
                'url'  => 'https://wa.me/919876543210'
            ],

        ];

        foreach ($socials as $social) {

            SocialLink::updateOrCreate(

                [
                    'icon' => $social['icon']
                ],

                $social
            );
        }
    }
}