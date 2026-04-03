<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run()
    {
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            MenuSeeder::class,
            OfferSeeder::class,
            OrderSeeder::class,
            NavigationMenuSeeder::class,
            BannerSeeder::class,
            CampaignSeeder::class,
            FeatureSeeder::class,
            NewsSeeder::class,
            TableSeeder::class,
        ]);
    }
    
}
