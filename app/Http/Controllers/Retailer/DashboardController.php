<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Models\Retailer;
use App\Models\WalletTransaction;
use App\Models\PanApplication;
use App\Models\PanCorrectionApplication;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $retailer = Retailer::where(
            'email',
            $user->email
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | RETAILER MODULES
        |--------------------------------------------------------------------------
        */

        $retailerMenus = Module::query()

            ->whereNull('parent_id')

            ->where('status', 1)

            ->where(function ($query) use ($user) {

                $query

                    ->whereHas(
                        'retailerAccess',
                        function ($q) use ($user) {

                            $q->where(
                                'retailer_id',
                                $user->id
                            );
                        }
                    )

                    ->orWhereHas(
                        'children.retailerAccess',
                        function ($q) use ($user) {

                            $q->where(
                                'retailer_id',
                                $user->id
                            );
                        }
                    );
            })

            ->with([

                'children' => function ($query) use ($user) {

                    $query

                        ->where('status', 1)

                        ->whereHas(
                            'retailerAccess',
                            function ($q) use ($user) {

                                $q->where(
                                    'retailer_id',
                                    $user->id
                                );
                            }
                        )

                        ->orderBy('sort_order')
                        ->orderBy('name');
                }

            ])

            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MODULE FLAGS
        |--------------------------------------------------------------------------
        */

        $moduleNames = $retailerMenus
            ->pluck('name')
            ->map(
                fn ($name) => strtolower($name)
            );

        $hasPanModule =
            $moduleNames->contains(
                fn ($name) => str_contains($name, 'pan')
            );

        $hasAadhaarModule =
            $moduleNames->contains(
                fn ($name) => str_contains($name, 'aadhaar')
            );

        $hasVerificationModule =
            $moduleNames->contains(
                fn ($name) => str_contains($name, 'verification')
            );

        $hasUtilityModule =
            $moduleNames->contains(
                fn ($name) => str_contains($name, 'utility')
            );

        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

        $panServices = $hasPanModule
            ? PanApplication::where(
                'user_id',
                $user->id
            )->count()
            : null;

        $panCorrectionServices = $hasPanModule
            ? PanCorrectionApplication::where(
                'user_id',
                $user->id
            )->count()
            : null;

        $aadhaarServices = $hasAadhaarModule
            ? 0
            : null;

        $totalVerifications = $hasVerificationModule
            ? 0
            : null;

        $utilityServices = $hasUtilityModule
            ? 0
            : null;

        $walletBalance =
            $user->wallet_balance ?? 0;

        $totalTransactions =
            WalletTransaction::where(
                'user_id',
                $user->id
            )->count();

        $approvedApplications =
            PanApplication::where(
                'user_id',
                $user->id
            )
            ->where(
                'status',
                'approved'
            )
            ->count();

        $totalApplications =
            PanApplication::where(
                'user_id',
                $user->id
            )
            ->count();

        $successRate =
            $totalApplications > 0
                ? round(
                    ($approvedApplications / $totalApplications) * 100
                )
                : 0;

        return view(
            'retailer.dashboard',
            [

                'user' => $user,
                'retailer' => $retailer,

                'retailerMenus' => $retailerMenus,

                'hasPanModule' => $hasPanModule,
                'hasAadhaarModule' => $hasAadhaarModule,
                'hasVerificationModule' => $hasVerificationModule,
                'hasUtilityModule' => $hasUtilityModule,

                'panServices' => $panServices,
                'panCorrectionService' => $panCorrectionServices,
                'aadhaarServices' => $aadhaarServices,
                'totalCustomers' => 0,
                'walletBalance' => $walletBalance,
                'totalVerifications' => $totalVerifications,
                'utilityServices' => $utilityServices,
                'totalTransactions' => $totalTransactions,
                'successRate' => $successRate,

                'notifications' => [],
                'notificationCount' => 0,
            ]
        );
    }
}
?>
