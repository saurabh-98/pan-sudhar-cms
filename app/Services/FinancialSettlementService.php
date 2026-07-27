<?php

namespace App\Services;

use App\Models\Charge;
use App\Models\FinancialLedger;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception;

class FinancialSettlementService
{
    protected ProfitDistributionService $profitDistribution;

    protected WalletService $walletService;

    public function __construct(
        ProfitDistributionService $profitDistribution,
        WalletService $walletService
    ) {
        $this->profitDistribution = $profitDistribution;
        $this->walletService = $walletService;
    }

    /**
     * Complete Financial Settlement
     */
    public function settle(
        string $serviceType,
        int $serviceId,
        string $referenceNo,
        float $serviceAmount,
        string $chargeCode,
        User $retailer,
        User $executive,
        ?string $remarks = null
    ): array {

        return DB::transaction(function () use (
            $serviceType,
            $serviceId,
            $referenceNo,
            $serviceAmount,
            $chargeCode,
            $retailer,
            $executive,
            $remarks
        ) {

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Settlement
            |--------------------------------------------------------------------------
            */

            if ($this->settlementExists($serviceType, $serviceId)) {
                throw new Exception(
                    'Financial settlement already completed.'
                );
            }
            /*
            |--------------------------------------------------------------------------
            | Load Charge
            |--------------------------------------------------------------------------
            */

            $charge = $this->getCharge(
                $chargeCode
            );

            /*
            |--------------------------------------------------------------------------
            | Commission
            |--------------------------------------------------------------------------
            */

            $executiveCommission = $this->getCommission(
                $charge,
                'Executive'
            );

            $distributorCommission = $this->getCommission(
                $charge,
                'Distributor'
            );

            /*
            |--------------------------------------------------------------------------
            | Distributor
            |--------------------------------------------------------------------------
            */

            $distributor = $this->getDistributor(
                $retailer
            );

            if (!$distributor) {

                $distributorCommission = 0;

            }

            /*
            |--------------------------------------------------------------------------
            | Net Business Profit
            |--------------------------------------------------------------------------
            */

            $netProfit = $this->calculateNetProfit(
                $serviceAmount,
                $executiveCommission,
                $distributorCommission
            );

            /*
            |--------------------------------------------------------------------------
            | Wallet Settlement
            |--------------------------------------------------------------------------
            */

            $wallet = $this->walletService->settle(

                executive: $executive,

                distributor: $distributor,

                executiveCommission: $executiveCommission,

                distributorCommission: $distributorCommission,

                referenceNo: $referenceNo,

                serviceType: $serviceType,

                serviceId: $serviceId

            );
                        /*
            |--------------------------------------------------------------------------
            | Partner Profit Distribution
            |--------------------------------------------------------------------------
            */

            $distribution = $this->profitDistribution->distribute(

                serviceType: $serviceType,

                serviceId: $serviceId,

                referenceNo: $referenceNo,

                serviceAmount: $serviceAmount,

                netProfit: $netProfit,

                executiveCommission: $executiveCommission,

                distributorCommission: $distributorCommission,

                remarks: $remarks

            );

            if (!$distribution['status']) {

                throw new Exception(

                    $distribution['message']
                    ?? 'Partner profit distribution failed.'

                );

            }

            /*
            |--------------------------------------------------------------------------
            | Financial Ledger
            |--------------------------------------------------------------------------
            */

            $ledger = FinancialLedger::create([

                'service_type'             => $serviceType,

                'service_id'               => $serviceId,

                'reference_no'             => $referenceNo,

                'charge_code'              => $chargeCode,

                'retailer_id'              => $retailer->id,

                'executive_id'             => $executive->id,

                'distributor_id'           => $distributor?->id,

                'service_amount'           => round($serviceAmount, 2),

                'executive_commission'     => round($executiveCommission, 2),

                'distributor_commission'   => round($distributorCommission, 2),

                'net_profit'               => round($netProfit, 2),

                'partner_profit'           => round($distribution['partner_profit'], 2),

                'company_profit'           => round($distribution['company_profit'], 2),

                'remarks'                  => $remarks,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Settlement Response
            |--------------------------------------------------------------------------
            */

            return [

                'status' => true,

                'ledger_id' => $ledger->id,

                'service_type' => $serviceType,

                'service_id' => $serviceId,

                'reference_no' => $referenceNo,

                'service_amount' => round($serviceAmount, 2),

                'executive_commission' => round($executiveCommission, 2),

                'distributor_commission' => round($distributorCommission, 2),

                'total_commission' => round(
                    $executiveCommission + $distributorCommission,
                    2
                ),

                'net_profit' => round($netProfit, 2),

                'partner_profit' => round(
                    $distribution['partner_profit'],
                    2
                ),

                'company_profit' => round(
                    $distribution['company_profit'],
                    2
                ),

                'wallet' => $wallet,

            ];

        });

    }

        /*
    |--------------------------------------------------------------------------
    | Get Charge Configuration
    |--------------------------------------------------------------------------
    */

    protected function getCharge(
        string $chargeCode
    ): Charge {

        $charge = Charge::with('commissions')
            ->active()
            ->where('code', $chargeCode)
            ->first();

        if (!$charge) {

            throw new ModelNotFoundException(
                "Charge configuration not found for [{$chargeCode}]."
            );

        }

        return $charge;

    }

    /*
    |--------------------------------------------------------------------------
    | Get Commission By Role
    |--------------------------------------------------------------------------
    */

    protected function getCommission(
        Charge $charge,
        string $role
    ): float {

        return round(

            (float) optional(

                $charge->commissions

                    ->where('role', $role)

                    ->where('is_active', true)

                    ->first()

            )->value,

            2

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Get Retailer's Distributor
    |--------------------------------------------------------------------------
    */

    protected function getDistributor(
        User $retailer
    ): ?User {

        $retailer->loadMissing(
            'retailer.distributor'
        );

        return optional(

            $retailer->retailer

        )->distributor;

    }

    /*
    |--------------------------------------------------------------------------
    | Has Settlement Already Been Done?
    |--------------------------------------------------------------------------
    */

    protected function settlementExists(
        string $serviceType,
        int $serviceId
    ): bool {

        return FinancialLedger::query()

            ->where('service_type', $serviceType)

            ->where('service_id', $serviceId)

            ->exists();

    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Net Profit
    |--------------------------------------------------------------------------
    */

    protected function calculateNetProfit(
        float $serviceAmount,
        float $executiveCommission,
        float $distributorCommission
    ): float {

        return round(

            max(

                0,

                $serviceAmount

                - $executiveCommission

                - $distributorCommission

            ),

            2

        );

    }

}