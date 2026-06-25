<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ItrFile;
use App\Models\Retailer;
use App\Models\PanApplication;
use App\Models\AadhaarService;
use App\Models\BankAccountService;
use App\Models\CscService;
use App\Models\WalletTransaction;
use App\Models\ServiceDocument;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DashboardRepository
{
    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    protected User $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    protected function isAdmin(): bool
    {
        return $this->user->hasRole('Admin');
    }

    protected function isDistributor(): bool
    {
        return $this->user->hasRole('Distributor');
    }

    protected function isExecutive(): bool
    {
        return $this->user->hasRole('Executive');
    }

    /*
    |--------------------------------------------------------------------------
    | DISTRIBUTOR RETAILERS
    |--------------------------------------------------------------------------
    */

    protected function distributorRetailerIds()
    {
        return Retailer::where(
            'distributor_id',
            $this->user->id
        )->pluck('id');
    }

    /*
    |--------------------------------------------------------------------------
    | APPLY PAN FILTER
    |--------------------------------------------------------------------------
    */

    protected function panQuery(): Builder
    {
        $query = PanApplication::query();

        if ($this->isAdmin()) {
            return $query;
        }

        if ($this->isExecutive()) {
            return $query->where(
                'assigned_to',
                $this->user->id
            );
        }

        if ($this->isDistributor()) {

            return $query->whereIn(
                'user_id',
                $this->distributorRetailerIds()
            );
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | APPLY ITR FILTER
    |--------------------------------------------------------------------------
    */

    protected function itrQuery(): Builder
    {
        $query = ItrFile::query();

        if ($this->isAdmin()) {
            return $query;
        }

        if ($this->isExecutive()) {
            return $query->where(
                'assigned_to',
                $this->user->id
            );
        }

        if ($this->isDistributor()) {

            return $query->whereIn(
                'user_id',
                $this->distributorRetailerIds()
            );
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | COUNT STATUS
    |--------------------------------------------------------------------------
    */

    protected function countPanStatus(string $status): int
    {
        return $this->panQuery()
            ->where('status', $status)
            ->count();
    }

    protected function countItrStatus(string $status): int
    {
        return $this->itrQuery()
            ->where('status', $status)
            ->count();
    }

    /*
|--------------------------------------------------------------------------
| TOTAL PAN APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getTotalPanApplications(): int
{
    return $this->panQuery()->count();
}

/*
|--------------------------------------------------------------------------
| TOTAL ITR APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getTotalItrApplications(): int
{
    return $this->itrQuery()->count();
}

/*
|--------------------------------------------------------------------------
| ASSIGNED APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getAssignedApplications(): int
{
    return $this->panQuery()
            ->whereNotNull('assigned_to')
            ->count()

        +

        $this->itrQuery()
            ->whereNotNull('assigned_to')
            ->count();
}

/*
|--------------------------------------------------------------------------
| COMPLETED APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getCompletedApplications(): int
{
    return

        $this->countPanStatus('Approved')

        +

        $this->countItrStatus('approved');
}

/*
|--------------------------------------------------------------------------
| PENDING APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getPendingApplications(): int
{
    return

        $this->countPanStatus('Pending')

        +

        $this->countItrStatus('pending');
}

/*
|--------------------------------------------------------------------------
| PROCESSING APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getProcessingApplications(): int
{
    return

        $this->countPanStatus('Processing')

        +

        $this->countItrStatus('processing');
}

/*
|--------------------------------------------------------------------------
| APPROVED APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getApprovedApplications(): int
{
    return

        $this->countPanStatus('Approved')

        +

        $this->countItrStatus('approved');
}

/*
|--------------------------------------------------------------------------
| REJECTED APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getRejectedApplications(): int
{
    return

        $this->countPanStatus('Rejected')

        +

        $this->countItrStatus('rejected');
}

/*
|--------------------------------------------------------------------------
| FRESH APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getFreshApplications(): int
{
    $pan = $this->panQuery();

    $itr = $this->itrQuery();

    if ($this->isAdmin()) {

        $pan->whereNull('assigned_to');

        $itr->whereNull('assigned_to');

    } else {

        $pan->where('status', 'Pending');

        $itr->where('status', 'pending');

    }

    return

        $pan->count()

        +

        $itr->count();
}

/*
|--------------------------------------------------------------------------
| AADHAAR QUERY
|--------------------------------------------------------------------------
*/

protected function aadhaarQuery(): Builder
{
    $query = AadhaarService::query();

    if ($this->isAdmin()) {
        return $query;
    }

    if ($this->isExecutive()) {
        return $query->where(
            'assigned_to',
            $this->user->id
        );
    }

    if ($this->isDistributor()) {

        return $query->whereIn(
            'user_id',
            $this->distributorRetailerIds()
        );
    }

    return $query;
}

/*
|--------------------------------------------------------------------------
| BANK ACCOUNT QUERY
|--------------------------------------------------------------------------
*/

protected function bankAccountQuery(): Builder
{
    $query = BankAccountService::query();

    if ($this->isAdmin()) {
        return $query;
    }

    if ($this->isExecutive()) {
        return $query->where(
            'assigned_to',
            $this->user->id
        );
    }

    if ($this->isDistributor()) {

        return $query->whereIn(
            'user_id',
            $this->distributorRetailerIds()
        );
    }

    return $query;
}

/*
|--------------------------------------------------------------------------
| CSC QUERY
|--------------------------------------------------------------------------
*/

protected function cscQuery(): Builder
{
    $query = CscService::query();

    if ($this->isAdmin()) {
        return $query;
    }

    if ($this->isExecutive()) {
        return $query->where(
            'assigned_to',
            $this->user->id
        );
    }

    if ($this->isDistributor()) {

        return $query->whereIn(
            'user_id',
            $this->distributorRetailerIds()
        );
    }

    return $query;
}

/*
|--------------------------------------------------------------------------
| TOTAL AADHAAR SERVICES
|--------------------------------------------------------------------------
*/

public function getTotalAadhaarServices(): int
{
    return $this->aadhaarQuery()->count();
}

/*
|--------------------------------------------------------------------------
| TOTAL BANK ACCOUNT SERVICES
|--------------------------------------------------------------------------
*/

public function getTotalBankAccountServices(): int
{
    return $this->bankAccountQuery()->count();
}

/*
|--------------------------------------------------------------------------
| TOTAL CSC SERVICES
|--------------------------------------------------------------------------
*/

public function getTotalCscServices(): int
{
    return $this->cscQuery()->count();
}

/*
|--------------------------------------------------------------------------
| WALLET TRANSACTIONS
|--------------------------------------------------------------------------
*/

public function getWalletTransactions(): int
{
    $query = WalletTransaction::query();

    if (!$this->isAdmin()) {
        $query->where(
            'user_id',
            $this->user->id
        );
    }

    return $query->count();
}

/*
|--------------------------------------------------------------------------
| TODAY UPLOADS
|--------------------------------------------------------------------------
*/

public function getTodayUploads(): int
{
    return ServiceDocument::query()

        ->whereDate(
            'created_at',
            today()
        )

        ->when(

            !$this->isAdmin(),

            function ($query) {

                $query->where(
                    'user_id',
                    $this->user->id
                );

            }

        )

        ->count();
}

/*
|--------------------------------------------------------------------------
| TOTAL REVENUE
|--------------------------------------------------------------------------
*/

public function getTotalRevenue()
{
    $query = WalletTransaction::query()
        ->where('type', 'credit');

    if (!$this->isAdmin()) {

        $query->where(
            'user_id',
            $this->user->id
        );

    }

    return $query->sum('amount');
}

/*
|--------------------------------------------------------------------------
| MONTH LABELS
|--------------------------------------------------------------------------
*/

public function getMonths(): array
{
    return [

        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',

    ];
}

/*
|--------------------------------------------------------------------------
| MONTHLY APPLICATIONS
|--------------------------------------------------------------------------
*/

public function getMonthlyApplications()
{
    return collect(range(1, 12))

        ->map(function ($month) {

            $pan = $this->panQuery()

                ->whereYear(
                    'created_at',
                    now()->year
                )

                ->whereMonth(
                    'created_at',
                    $month
                )

                ->count();

            $itr = $this->itrQuery()

                ->whereYear(
                    'created_at',
                    now()->year
                )

                ->whereMonth(
                    'created_at',
                    $month
                )

                ->count();

            return $pan + $itr;

        })

        ->toArray();
}

/*
|--------------------------------------------------------------------------
| MONTHLY PAN
|--------------------------------------------------------------------------
*/

public function getMonthlyPanApplications()
{
    return collect(range(1, 12))

        ->map(function ($month) {

            return $this->panQuery()

                ->whereYear(
                    'created_at',
                    now()->year
                )

                ->whereMonth(
                    'created_at',
                    $month
                )

                ->count();

        })

        ->toArray();
}

/*
|--------------------------------------------------------------------------
| MONTHLY ITR
|--------------------------------------------------------------------------
*/

public function getMonthlyItrApplications()
{
    return collect(range(1, 12))

        ->map(function ($month) {

            return $this->itrQuery()

                ->whereYear(
                    'created_at',
                    now()->year
                )

                ->whereMonth(
                    'created_at',
                    $month
                )

                ->count();

        })

        ->toArray();
}

/*
|--------------------------------------------------------------------------
| MONTHLY REVENUE
|--------------------------------------------------------------------------
*/

public function getMonthlyRevenue()
{
    return collect(range(1, 12))

        ->map(function ($month) {

            $query = WalletTransaction::query()

                ->where('type', 'credit')

                ->whereYear(
                    'created_at',
                    now()->year
                )

                ->whereMonth(
                    'created_at',
                    $month
                );

            if (!$this->isAdmin()) {

                $query->where(
                    'user_id',
                    $this->user->id
                );

            }

            return (float) $query->sum('amount');

        })

        ->toArray();
}

/*
|--------------------------------------------------------------------------
| DASHBOARD SUMMARY
|--------------------------------------------------------------------------
*/

public function getDashboardSummary(): array
{
    return [

        'totalPanApplications'      => $this->getTotalPanApplications(),

        'totalItrApplications'      => $this->getTotalItrApplications(),

        'totalAadhaarServices'      => $this->getTotalAadhaarServices(),

        'totalBankAccounts'         => $this->getTotalBankAccountServices(),

        'totalCscServices'          => $this->getTotalCscServices(),

        'assignedApplications'      => $this->getAssignedApplications(),

        'completedApplications'     => $this->getCompletedApplications(),

        'freshApplications'         => $this->getFreshApplications(),

        'processingApplications'    => $this->getProcessingApplications(),

        'pendingApplications'       => $this->getPendingApplications(),

        'approvedApplications'      => $this->getApprovedApplications(),

        'rejectedApplications'      => $this->getRejectedApplications(),

        'todayUploads'              => $this->getTodayUploads(),

        'walletTransactions'        => $this->getWalletTransactions(),

        'totalRevenue'              => $this->getTotalRevenue(),

        'months'                    => $this->getMonths(),

        'chartData'                 => $this->getMonthlyApplications(),

        'panChartData'              => $this->getMonthlyPanApplications(),

        'itrChartData'              => $this->getMonthlyItrApplications(),

        'revenueChartData'          => $this->getMonthlyRevenue(),

    ];
}

}