<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NavigationMenu;

class NavigationMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [

            /*
            |--------------------------------------------------------------------------
            | HOME
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'Home',
                'url'    => '/',
                'order'  => 1,
                'status' => 1
            ],

          
            /*
            |--------------------------------------------------------------------------
            | ABOUT US
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'About Us',
                'url'    => '/about-us',
                'order'  => 9,
                'status' => 1
            ],

            /*
            |--------------------------------------------------------------------------
            | CONTACT US
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'Contact Us',
                'url'    => '/contact-us',
                'order'  => 10,
                'status' => 1
            ],

        ];

        foreach ($menus as $menu) {

            NavigationMenu::updateOrCreate(

                ['name' => $menu['name']],

                $menu

            );
        }
    }
}