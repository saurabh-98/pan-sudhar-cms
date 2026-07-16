<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ReferralReward;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class ReleaseReferralRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan referral:release
     */
    protected $signature = 'referral:release';

    /**
     * The console command description.
     */
    protected $description = 'Release scheduled referral rewards';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rewards = ReferralReward::where(
                'status',
                'Approved'
            )
            ->where(
                'wallet_credited',
                false
            )
            ->whereNotNull('release_at')
            ->where(
                'release_at',
                '<=',
                now()
            )
            ->get();

        if ($rewards->isEmpty()) {

            $this->info('No referral rewards to release.');

            return self::SUCCESS;

        }

        foreach ($rewards as $reward) {

            DB::transaction(function () use ($reward) {

                /*
                |--------------------------------------------------------------------------
                | Wallet
                |--------------------------------------------------------------------------
                */

                $wallet = Wallet::firstOrCreate(

                    [

                        'retailer_id' => $reward->referrer_id

                    ],

                    [

                        'balance' => 0

                    ]

                );

                /*
                |--------------------------------------------------------------------------
                | Credit Wallet
                |--------------------------------------------------------------------------
                */

                $wallet->increment(

                    'balance',

                    $reward->reward

                );

                /*
                |--------------------------------------------------------------------------
                | Wallet Transaction
                |--------------------------------------------------------------------------
                */

                WalletTransaction::create([

                    'retailer_id'      => $reward->referrer_id,

                    'amount'           => $reward->reward,

                    'transaction_type' => 'credit',

                    'type'             => 'Referral Bonus',

                    'remarks'          => 'Referral reward released.',

                ]);

                /*
                |--------------------------------------------------------------------------
                | Update Reward
                |--------------------------------------------------------------------------
                */

                $reward->update([

                    'wallet_credited' => true,

                    'status' => 'Released',

                    'remarks' => 'Wallet credited successfully.'

                ]);

            });

            $this->info(

                "Reward Released : Retailer ID {$reward->referrer_id}"

            );

        }

        return self::SUCCESS;
    }
}