<?php

namespace App\Services;

use App\Models\ProfitPartner;
use App\Models\ProfitPartnerTransaction;

class ProfitDistributionService
{
    /**
     * Distribute Partner Profit
     */
    public function distribute(

        string $serviceType,

        int $serviceId,

        ?string $referenceNo,

        float $serviceAmount,

        float $netProfit,

        float $executiveCommission = 0,

        float $distributorCommission = 0,

        ?string $remarks = null

    ): array {

        /*
        |--------------------------------------------------------------------------
        | No Profit
        |--------------------------------------------------------------------------
        */

        if ($netProfit <= 0) {

            return [

                'status' => true,

                'net_profit' => round($netProfit, 2),

                'partner_profit' => 0,

                'company_profit' => round($netProfit, 2),

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Active Partners
        |--------------------------------------------------------------------------
        */

        $partners = ProfitPartner::active()->get();

        if ($partners->isEmpty()) {

            return [

                'status' => true,

                'net_profit' => round($netProfit, 2),

                'partner_profit' => 0,

                'company_profit' => round($netProfit, 2),

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Validate Total Percentage
        |--------------------------------------------------------------------------
        */

        $totalPercentage = $partners->sum('profit_percentage');

        if ($totalPercentage > 100) {

            throw new \RuntimeException(
                'Total partner profit percentage cannot exceed 100%.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Distribute Profit
        |--------------------------------------------------------------------------
        */

        $totalPartnerProfit = 0;

        foreach ($partners as $partner) {

            $partnerProfit = round(

                ($netProfit * $partner->profit_percentage) / 100,

                2

            );

            $totalPartnerProfit += $partnerProfit;

            ProfitPartnerTransaction::create([

                'partner_id' => $partner->id,

                'service_type' => $serviceType,

                'service_id' => $serviceId,

                'reference_no' => $referenceNo,

                'service_amount' => $serviceAmount,

                'executive_commission' => $executiveCommission,

                'distributor_commission' => $distributorCommission,

                'net_profit' => $netProfit,

                'profit_percentage' => $partner->profit_percentage,

                'profit_amount' => $partnerProfit,

                'remarks' => $remarks,

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Company Profit
        |--------------------------------------------------------------------------
        */

        $companyProfit = round(
            $netProfit - $totalPartnerProfit,
            2
        );

        return [

            'status' => true,

            'net_profit' => round($netProfit, 2),

            'partner_profit' => round($totalPartnerProfit, 2),

            'company_profit' => $companyProfit,

        ];

    }
}