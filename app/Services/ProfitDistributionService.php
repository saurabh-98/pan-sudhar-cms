<?php

namespace App\Services;

use App\Models\ProfitPartner;
use App\Models\ProfitPartnerTransaction;
use Illuminate\Support\Facades\DB;

class ProfitDistributionService
{
    /**
     * Distribute Profit
     */
    public function distribute(

        string $serviceType,

        int $serviceId,

        ?string $referenceNo,

        float $serviceAmount,

        float $executiveCommission = 0,

        float $distributorCommission = 0,

        ?string $remarks = null

    ): array {

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Net Business Profit
            |--------------------------------------------------------------------------
            */

            $netProfit =

                $serviceAmount
                - $executiveCommission
                - $distributorCommission;

            if ($netProfit <= 0) {

                DB::rollBack();

                return [

                    'status' => false,

                    'message' => 'Net profit is zero.',

                ];

            }

            /*
            |--------------------------------------------------------------------------
            | Active Partners
            |--------------------------------------------------------------------------
            */

            $partners = ProfitPartner::active()->get();

            $totalPartnerProfit = 0;

            /*
            |--------------------------------------------------------------------------
            | Distribute Partner Profit
            |--------------------------------------------------------------------------
            */

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

            $companyProfit =

                $netProfit
                - $totalPartnerProfit;

            DB::commit();

            return [

                'status' => true,

                'net_profit' => round($netProfit,2),

                'partner_profit' => round($totalPartnerProfit,2),

                'company_profit' => round($companyProfit,2),

            ];

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return [

                'status' => false,

                'message' => $e->getMessage(),

            ];

        }

    }
}