<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            [
                'name' => 'Paneer Butter Masala',
                'price' => 249,
                'type' => 'veg',
                'category_id' => 1,
                'description' => 'Creamy paneer curry cooked in rich tomato butter gravy.',
                'specifications' => 'Spicy: Medium | Serve: 2 | Type: Veg | Cuisine: North Indian'
            ],
            [
                'name' => 'Chicken Biryani',
                'price' => 299,
                'type' => 'non-veg',
                'category_id' => 1,
                'description' => 'Aromatic basmati rice cooked with tender chicken and spices.',
                'specifications' => 'Spicy: High | Serve: 2 | Type: Non-Veg | Cuisine: Mughlai'
            ],
            [
                'name' => 'Veg Spring Roll',
                'price' => 129,
                'type' => 'veg',
                'category_id' => 2,
                'description' => 'Crispy rolls stuffed with fresh vegetables.',
                'specifications' => 'Spicy: Mild | Serve: 1 | Type: Veg | Starter'
            ],
            [
                'name' => 'Paneer Tikka',
                'price' => 179,
                'type' => 'veg',
                'category_id' => 2,
                'description' => 'Grilled paneer cubes marinated in spices.',
                'specifications' => 'Spicy: Medium | Serve: 1 | Protein Rich | Starter'
            ],
            [
                'name' => 'Chicken Tikka',
                'price' => 199,
                'type' => 'non-veg',
                'category_id' => 3,
                'description' => 'Juicy chicken pieces grilled with Indian spices.',
                'specifications' => 'Spicy: Medium | Serve: 1 | High Protein'
            ],
            [
                'name' => 'Fish Fry',
                'price' => 229,
                'type' => 'non-veg',
                'category_id' => 3,
                'description' => 'Crispy fried fish with tangy spices.',
                'specifications' => 'Spicy: Medium | Serve: 1 | Seafood'
            ],
            [
                'name' => 'Chocolate Cake',
                'price' => 149,
                'type' => 'veg',
                'category_id' => 4,
                'description' => 'Soft and rich chocolate layered cake.',
                'specifications' => 'Sweet | Serve: 1 | Dessert'
            ],
            [
                'name' => 'Gulab Jamun',
                'price' => 99,
                'type' => 'veg',
                'category_id' => 4,
                'description' => 'Soft milk balls soaked in sugar syrup.',
                'specifications' => 'Sweet | Serve: 2 | Indian Dessert'
            ],
            [
                'name' => 'Cold Drink',
                'price' => 59,
                'type' => 'veg',
                'category_id' => 5,
                'description' => 'Refreshing chilled soft drink.',
                'specifications' => 'Cold | Serve: 1 | Beverage'
            ],
            [
                'name' => 'Lassi',
                'price' => 79,
                'type' => 'veg',
                'category_id' => 5,
                'description' => 'Traditional yogurt-based drink.',
                'specifications' => 'Sweet | Serve: 1 | Cooling Drink'
            ],
            [
                'name' => 'Tomato Soup',
                'price' => 89,
                'type' => 'veg',
                'category_id' => 6,
                'description' => 'Hot and tangy tomato soup.',
                'specifications' => 'Hot | Serve: 1 | Soup'
            ],
            [
                'name' => 'Chicken Soup',
                'price' => 109,
                'type' => 'non-veg',
                'category_id' => 6,
                'description' => 'Warm chicken soup with herbs.',
                'specifications' => 'Hot | Serve: 1 | Protein Rich'
            ],
        ];

        foreach ($menus as $index => $menu) {

            $menu['image'] = 'https://picsum.photos/300?random=' . rand(100,999); // 🔥 dynamic image

            Menu::create($menu);
        }
    }
}