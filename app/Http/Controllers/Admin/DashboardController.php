<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;
use App\Models\User;
use App\Models\AadhaarService;
use App\Models\BankAccountService;
use App\Models\CscService;
use App\Models\WalletTransaction;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SERVICE
    |--------------------------------------------------------------------------
    */

    protected DashboardService $service;

    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | ROLE FLAGS
        |--------------------------------------------------------------------------
        */

        $isAdmin = $user->hasRole('admin');
        $isSuperDistributor = $user->hasRole('Super Distributor');
        $isDistributor = $user->hasRole('Distributor');
        $isExecutive = $user->hasRole('Executive');

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD DATA
        |--------------------------------------------------------------------------
        */

        $data = $this->service->getDashboardData();

        /*
        |--------------------------------------------------------------------------
        | USER COUNTS
        |--------------------------------------------------------------------------
        */

        $totalRetailers = $isAdmin
            ? User::role('Retailer')->count()
            : ($data['totalRetailers'] ?? 0);

        // Admin sees every distributor; Super Distributor sees only the
        // distributors they personally created (via the `created_by` column).
        $totalDistributors = $isAdmin
            ? User::role('Distributor')->count()
            : ($isSuperDistributor
                ? User::role('Distributor')->where('created_by', $user->id)->count()
                : 0);

        $totalExecutives = $isAdmin
            ? User::role('Executive')->count()
            : 0;

        $totalUsers = $isAdmin
            ? User::count()
            : $totalRetailers;

        /*
        |--------------------------------------------------------------------------
        | SERVICE COUNTS
        |--------------------------------------------------------------------------
        */

        $totalAadhaarServices = $data['totalAadhaarServices']
            ?? AadhaarService::count();

        $totalBankAccounts = $data['totalBankAccounts']
            ?? BankAccountService::count();

        $totalCscServices = $data['totalCscServices']
            ?? CscService::count();

        $walletTransactions = $data['walletTransactions']
            ?? WalletTransaction::count();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', [

            'isAdmin' => $isAdmin,
            'isSuperDistributor' => $isSuperDistributor,
            'isDistributor' => $isDistributor,
            'isExecutive' => $isExecutive,

            'totalPanApplications' => $data['totalPanApplications'] ?? 0,
            'totalItrApplications' => $data['totalItrApplications'] ?? 0,

            'totalRetailers' => $totalRetailers,
            'totalDistributors' => $totalDistributors,
            'totalExecutives' => $totalExecutives,
            'totalUsers' => $totalUsers,

            'totalAadhaarServices' => $totalAadhaarServices,
            'totalBankAccounts' => $totalBankAccounts,
            'totalCscServices' => $totalCscServices,

            'walletTransactions' => $walletTransactions,

            'assignedApplications' => $data['assignedApplications'] ?? 0,
            'completedApplications' => $data['completedApplications'] ?? 0,
            'freshApplications' => $data['freshApplications'] ?? 0,
            'processingApplications' => $data['processingApplications'] ?? 0,
            'pendingApplications' => $data['pendingApplications'] ?? 0,
            'approvedApplications' => $data['approvedApplications'] ?? 0,
            'rejectedApplications' => $data['rejectedApplications'] ?? 0,

            'todayUploads' => $data['todayUploads'] ?? 0,
            'totalRevenue' => $data['totalRevenue'] ?? 0,

            'months' => $data['months'] ?? [],
            'chartData' => $data['chartData'] ?? [],

            'panChartData' => $data['panChartData'] ?? [],
            'itrChartData' => $data['itrChartData'] ?? [],
            'revenueChartData' => $data['revenueChartData'] ?? [],
        ]);
    }
}