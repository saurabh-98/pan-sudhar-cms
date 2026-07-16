<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PopupAnnouncement;

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

                'title' => '🎉 Refer a Retailer & Earn ₹100',

                'description' => '

                    <div class="referral-terms">

                        <h5>
                            Refer a Retailer & Earn <span style="color:#28a745;">₹100</span>
                            on Every Successful Referral!
                        </h5>

                        <p class="mb-3">
                            Invite new retailers using your referral link.
                            Once all eligibility criteria are met and the referred retailer is approved,
                            your referral reward will be credited automatically.
                        </p>

                        <div class="alert alert-warning">

                            <strong>Terms & Conditions</strong>

                            <ol class="mt-2 mb-0">

                                <li>
                                    You must have completed
                                    <strong>₹1,000 or more</strong>
                                    business during the previous month.
                                </li>

                                <li>
                                    Your retailer account must be at least
                                    <strong>90 days old</strong>.
                                </li>

                                <li>
                                    The referred retailer must successfully complete registration
                                    and receive admin approval.
                                </li>

                                <li>
                                    Fake, duplicate, or self-referrals are strictly prohibited.
                                    Duplicate mobile numbers, Aadhaar, PAN, email addresses,
                                    or documents will lead to rejection.
                                </li>

                                <li>
                                    Referral rewards are credited within
                                    <strong>7–15 working days</strong>
                                    after successful verification.
                                </li>

                                <li>
                                    The company reserves the right to reject any referral
                                    that violates the referral policy.
                                </li>

                            </ol>

                        </div>

                    </div>

                ',

                'image' => null,

                /*
                |--------------------------------------------------------------------------
                | Dynamic referral link is generated in Blade
                |--------------------------------------------------------------------------
                */

                'button_text' => 'Copy Referral Link',

                'button_link' => '#',

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