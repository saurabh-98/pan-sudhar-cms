<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PopupAnnouncement;
use Illuminate\Support\Str;

class PopupAnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PopupAnnouncement::updateOrCreate(

            [
                'slug' => 'refer-and-earn'
            ],

            [
                'title' => 'Refer a Retailer & Earn ₹100',

                'description' => '
                    <h5>🎉 Refer a Retailer & Earn ₹100 on Every Successful Referral!</h5>

                    <p><strong>Terms & Conditions Apply</strong></p>

                    <ol>
                        <li>Please ensure that you have used our services worth more than <strong>₹1,000</strong> during the last month.</li>

                        <li>You must have been an active retailer with us for at least <strong>90 days</strong> to be eligible.</li>

                        <li>The referral bonus will be provided only after the referred retailer has been successfully verified.</li>

                        <li>Duplicate, fake, or multiple referrals using the same person, mobile number, or documents will not be accepted.</li>

                        <li>The referral bonus will be credited to your wallet within <strong>7–15 working days</strong>.</li>
                    </ol>
                ',

                'image' => null,

                'button_text' => 'Refer Now',

                'button_link' => '/retailer/refer',

                'background_color' => '#ffffff',

                'text_color' => '#000000',

                'show_on_login' => true,

                'show_on_dashboard' => true,

                'show_once_per_day' => true,

                'start_date' => now()->toDateString(),

                'end_date' => now()->addYears(5)->toDateString(),

                'priority' => 1,

                'status' => true,
            ]

        );
    }
}