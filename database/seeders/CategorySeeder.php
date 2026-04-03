<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            [
                'name' => 'Main Course',
                'slug' => Str::slug('Main Course'),
                'image' => 'https://picsum.photos/300?random=11'
            ],
            [
                'name' => 'Veg Starter',
                'slug' => Str::slug('Veg Starter'),
                'image' => 'https://picsum.photos/300?random=12'
            ],
            [
                'name' => 'Non-Veg Starter',
                'slug' => Str::slug('Non-Veg Starter'),
                'image' => 'https://picsum.photos/300?random=13'
            ],
            [
                'name' => 'Desserts',
                'slug' => Str::slug('Desserts'),
                'image' => 'https://picsum.photos/300?random=14'
            ],
            [
                'name' => 'Beverages',
                'slug' => Str::slug('Beverages'),
                'image' => 'https://picsum.photos/300?random=15'
            ],
            [
                'name' => 'Soup',
                'slug' => Str::slug('Soup'),
                'image' => 'https://picsum.photos/300?random=16'
            ],
        ]);
    }
}