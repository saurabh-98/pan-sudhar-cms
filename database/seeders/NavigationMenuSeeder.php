<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NavigationMenu;

class NavigationMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Home',
                'url' => '/',
                'order' => 1,
                'status' => 1
            ],
            [
                'name' => 'Menu',
                'url' => '/menu',
                'order' => 2,
                'status' => 1
            ],
            [
                'name' => 'Contact Us',
                'url' => '/contact',
                'order' => 3,
                'status' => 1
            ],
            [
                'name' => 'About Us',
                'url' => '/about',
                'order' => 4,
                'status' => 1
            ],
            [
                'name' => 'Track Order',
                'url' => '/track-order',
                'order' => 5,
                'status' => 1
            ],
            [
                'name' => 'Reservation',
                'url' => '/reservation',
                'order' => 6,
                'status' => 1
            ],
        ];

        foreach ($menus as $menu) {
            NavigationMenu::updateOrCreate(
                ['name' => $menu['name']], // prevent duplicate
                $menu
            );
        }
    }
}