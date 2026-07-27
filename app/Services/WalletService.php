<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WalletService
{
    /**
     * Wallet Settlement
     */
    public function settle(
        User $executive,
        ?User $distributor,
        float $executiveCommission,
        float $distributorCommission,
        string $referenceNo,
        string $serviceType,
        int $serviceId
    ): array {

        $admin = $this->getAdmin();

        /*
        |--------------------------------------------------------------------------
        | Executive Commission
        |--------------------------------------------------------------------------
        */

        if ($executiveCommission > 0) {

            $this->credit(
                user: $executive,
                amount: $executiveCommission,
                receiver: $admin,
                referenceNo: $referenceNo,
                serviceType: $serviceType,
                serviceId: $serviceId,
                remark: "{$serviceType} Executive Commission"
            );

            $this->debit(
                user: $admin,
                amount: $executiveCommission,
                receiver: $executive,
                referenceNo: $referenceNo,
                serviceType: $serviceType,
                serviceId: $serviceId,
                remark: "{$serviceType} Executive Commission"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Distributor Commission
        |--------------------------------------------------------------------------
        */

        if ($distributor && $distributorCommission > 0) {

            $this->credit(
                user: $distributor,
                amount: $distributorCommission,
                receiver: $admin,
                referenceNo: $referenceNo,
                serviceType: $serviceType,
                serviceId: $serviceId,
                remark: "{$serviceType} Distributor Commission"
            );

            $this->debit(
                user: $admin,
                amount: $distributorCommission,
                receiver: $distributor,
                referenceNo: $referenceNo,
                serviceType: $serviceType,
                serviceId: $serviceId,
                remark: "{$serviceType} Distributor Commission"
            );
        }

        return [

            'status' => true,

            'executive_commission' => round($executiveCommission, 2),

            'distributor_commission' => round($distributorCommission, 2),

            'total_commission' => round(
                $executiveCommission + $distributorCommission,
                2
            ),

        ];
    }

    /**
     * Credit Wallet
     */
    public function credit(
        User $user,
        float $amount,
        ?User $receiver,
        string $referenceNo,
        string $serviceType,
        int $serviceId,
        string $remark
    ): void {

        if ($amount <= 0) {
            return;
        }

        $user->increment('wallet_balance', $amount);

        WalletTransaction::create([

            'user_id'          => $user->id,

            'receiver_id'      => $receiver?->id,

            'amount'           => $amount,

            'type'             => WalletTransaction::TYPE_CREDIT,

            'transaction_type' => WalletTransaction::TXN_SERVICE_COMMISSION,

            'service_type'     => $serviceType,

            'service_id'       => $serviceId,

            'reference_no'     => $referenceNo,

            'remark'           => $remark,

        ]);
    }

    /**
     * Debit Wallet
     */
    public function debit(
        User $user,
        float $amount,
        ?User $receiver,
        string $referenceNo,
        string $serviceType,
        int $serviceId,
        string $remark
    ): void {

        if ($amount <= 0) {
            return;
        }

        $user->decrement('wallet_balance', $amount);

        WalletTransaction::create([

            'user_id'          => $user->id,

            'receiver_id'      => $receiver?->id,

            'amount'           => $amount,

            'type'             => WalletTransaction::TYPE_DEBIT,

            'transaction_type' => WalletTransaction::TXN_SERVICE_COMMISSION,

            'service_type'     => $serviceType,

            'service_id'       => $serviceId,

            'reference_no'     => $referenceNo,

            'remark'           => $remark,

        ]);
    }

    /**
     * Reverse Credit
     */
    public function reverseCredit(
        User $user,
        float $amount,
        string $referenceNo,
        string $serviceType,
        int $serviceId,
        string $remark
    ): void {

        if ($amount <= 0) {
            return;
        }

        $user->decrement('wallet_balance', $amount);

        WalletTransaction::create([

            'user_id'          => $user->id,

            'amount'           => $amount,

            'type'             => WalletTransaction::TYPE_DEBIT,

            'transaction_type' => WalletTransaction::TXN_REVERSE,

            'service_type'     => $serviceType,

            'service_id'       => $serviceId,

            'reference_no'     => $referenceNo,

            'remark'           => $remark,

        ]);
    }

    /**
     * Reverse Debit
     */
    public function reverseDebit(
        User $user,
        float $amount,
        string $referenceNo,
        string $serviceType,
        int $serviceId,
        string $remark
    ): void {

        if ($amount <= 0) {
            return;
        }

        $user->increment('wallet_balance', $amount);

        WalletTransaction::create([

            'user_id'          => $user->id,

            'amount'           => $amount,

            'type'             => WalletTransaction::TYPE_CREDIT,

            'transaction_type' => WalletTransaction::TXN_REVERSE,

            'service_type'     => $serviceType,

            'service_id'       => $serviceId,

            'reference_no'     => $referenceNo,

            'remark'           => $remark,

        ]);
    }

    /**
     * Get Admin
     */
    protected function getAdmin(): User
    {
        return User::role('Admin')->firstOr(function () {
            throw new ModelNotFoundException('Admin user not found.');
        });
    }
}