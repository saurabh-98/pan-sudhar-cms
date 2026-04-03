<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;

class OfferSeeder extends Seeder
{
    public function run()
    {
        Offer::insert([

            [
                'title' => 'Flat 50% OFF',
                'type' => 'percent',
                'value' => 50,
                'code' => 'HALF50',
                'min_order' => 299,
                'max_discount' => 150,
                'menu_id' => null,
                'category_id' => null,
                'expires_at' => now()->addDays(10),
                'is_active' => 1
            ],

            [
                'title' => 'Save ₹100',
                'type' => 'fixed',
                'value' => 100,
                'code' => 'SAVE100',
                'min_order' => 499,
                'max_discount' => null,
                'menu_id' => null,
                'category_id' => null,
                'expires_at' => now()->addDays(7),
                'is_active' => 1
            ],

            [
                'title' => '20% OFF on Starters',
                'type' => 'percent',
                'value' => 20,
                'code' => 'START20',
                'min_order' => 199,
                'max_discount' => 80,
                'menu_id' => null,
                'category_id' => 2, // Veg Starter
                'expires_at' => now()->addDays(5),
                'is_active' => 1
            ],

            [
                'title' => 'Free Drink Offer',
                'type' => 'freebie',
                'value' => 0,
                'code' => 'FREE_DRINK',
                'min_order' => 599,
                'max_discount' => null,
                'menu_id' => null,
                'category_id' => 5, // Beverages
                'expires_at' => now()->addDays(15),
                'is_active' => 1
            ],

        ]);
    }
}