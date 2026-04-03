<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FooterLink;
use App\Models\Setting;
use App\Models\SocialLink;

class FooterSeeder extends Seeder
{
    public function run()
    {
        /* =========================
           FOOTER LINKS
        ========================= */

        $links = [

            // QUICK LINKS
            ['section' => 'quick_links', 'name' => 'Home', 'url' => '/', 'sort_order' => 1],
            ['section' => 'quick_links', 'name' => 'Menu', 'url' => '/menu', 'sort_order' => 2],
            ['section' => 'quick_links', 'name' => 'Reservations', 'url' => '/reservations', 'sort_order' => 3],
            ['section' => 'quick_links', 'name' => 'Order Online', 'url' => '/order', 'sort_order' => 4],

            // SERVICES
            ['section' => 'services', 'name' => 'Food Delivery', 'url' => '/delivery', 'sort_order' => 1],
            ['section' => 'services', 'name' => 'Table Booking', 'url' => '/booking', 'sort_order' => 2],
            ['section' => 'services', 'name' => 'Catering Services', 'url' => '/catering', 'sort_order' => 3],
            ['section' => 'services', 'name' => 'Bulk Orders', 'url' => '/bulk-orders', 'sort_order' => 4],

            // SUPPORT
            ['section' => 'support', 'name' => 'Help Center', 'url' => '/help', 'sort_order' => 1],
            ['section' => 'support', 'name' => 'FAQs', 'url' => '/faqs', 'sort_order' => 2],
            ['section' => 'support', 'name' => 'Contact Us', 'url' => '/contact', 'sort_order' => 3],
            ['section' => 'support', 'name' => 'Refund Policy', 'url' => '/refund-policy', 'sort_order' => 4],

            // COMPANY
            ['section' => 'company', 'name' => 'About Us', 'url' => '/about', 'sort_order' => 1],
            ['section' => 'company', 'name' => 'Careers', 'url' => '/careers', 'sort_order' => 2],
            ['section' => 'company', 'name' => 'Privacy Policy', 'url' => '/privacy', 'sort_order' => 3],
            ['section' => 'company', 'name' => 'Terms & Conditions', 'url' => '/terms', 'sort_order' => 4],
        ];

        foreach ($links as $link) {
            FooterLink::create($link);
        }

        /* =========================
           SETTINGS
        ========================= */

        Setting::updateOrCreate(
            ['key' => 'footer_text'],
            ['value' => '© ' . date('Y') . ' Foodies Restaurant. All rights reserved.']
        );

        Setting::updateOrCreate(
            ['key' => 'footer_tagline'],
            ['value' => 'Serving delicious food with love ❤️']
        );

        /* =========================
           SOCIAL LINKS
        ========================= */

        $socials = [
            ['icon' => 'fa-facebook', 'url' => 'https://facebook.com/foodies'],
            ['icon' => 'fa-instagram', 'url' => 'https://instagram.com/foodies'],
            ['icon' => 'fa-twitter', 'url' => 'https://twitter.com/foodies'],
            ['icon' => 'fa-youtube', 'url' => 'https://youtube.com/foodies'],
        ];

        foreach ($socials as $social) {
            SocialLink::create($social);
        }
    }
}