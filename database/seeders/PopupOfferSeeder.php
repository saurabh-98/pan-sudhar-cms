<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PopupOffer;
use Carbon\Carbon;

class PopupOfferSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 Prevent duplicate popup
        PopupOffer::updateOrCreate(
            ['title' => '🎉 Special Festive Offer!'], // unique condition

            [
                'description' => 'Get Flat 30% OFF on your first order. Limited time only!',
                'image' => 'uploads/offers/festival.jpg',
                'is_active' => 1,
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addDays(7),
            ]
        );
    }
}