<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        Campaign::truncate();

        Campaign::insert([
            [
                'tag' => 'Hot Deal',
                'title' => 'Burger Combo',
                'description' => 'Burger + Fries + Coke',
                'price' => 199,
                'image' => 'campaigns/demo1.jpg',
                'is_active' => 1
            ],
            [
                'tag' => 'Special',
                'title' => 'Pizza Offer',
                'description' => 'Buy 1 Get 1 Free',
                'price' => 299,
                'image' => 'campaigns/demo2.jpg',
                'is_active' => 1
            ]
        ]);
    }
}