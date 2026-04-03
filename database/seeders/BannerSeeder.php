<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::truncate();

        Banner::create([
            'title' => 'Delicious Food Delivered Fast',
            'subtitle' => 'Fresh • Fast • Premium Taste',
            'button_text' => 'Explore Menu',
            'image' => 'banners/demo.jpg',
            'type' => 'hero',
            'is_active' => 1
        ]);
    }
}