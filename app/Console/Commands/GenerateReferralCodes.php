<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Retailer;

class GenerateReferralCodes extends Command
{
    protected $signature = 'referral:generate-codes';

    protected $description = 'Generate referral codes for existing retailers';

    public function handle()
    {
        $retailers = Retailer::whereNull('referral_code')->get();

        foreach ($retailers as $retailer) {

            $retailer->update([
                'referral_code' => 'RT' . str_pad($retailer->id, 6, '0', STR_PAD_LEFT)
            ]);

            $this->info("Generated: {$retailer->referral_code}");
        }

        $this->info('All referral codes generated successfully.');

        return self::SUCCESS;
    }
}