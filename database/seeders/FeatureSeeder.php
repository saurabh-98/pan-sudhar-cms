<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        Feature::truncate();

        Feature::insert([
            [
                'icon'=>'🚀',
                'title'=>'Fast Delivery',
                'description'=>'Get your food within 30 minutes',
                'is_active'=>1
            ],
            [
                'icon'=>'🍔',
                'title'=>'Fresh Food',
                'description'=>'Prepared with fresh ingredients',
                'is_active'=>1
            ],
            [
                'icon'=>'💳',
                'title'=>'Easy Payment',
                'description'=>'Multiple secure payment options',
                'is_active'=>1
            ]
        ]);
    }
}