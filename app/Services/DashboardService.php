<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /*
    |--------------------------------------------------------------------------
    | REPOSITORY
    |--------------------------------------------------------------------------
    */

    protected DashboardRepository $repo;

    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD DATA
    |--------------------------------------------------------------------------
    */

    public function getDashboardData(): array
    {
        $user = Auth::user();

        $cacheKey = 'dashboard_stats_' . $user->id;

        return Cache::remember(

            $cacheKey,

            now()->addMinutes(5),

            function () {

                return $this->repo->getDashboardSummary();

            }

        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAR CACHE
    |--------------------------------------------------------------------------
    */

    public function clearDashboardCache(int $userId): void
    {
        Cache::forget(
            'dashboard_stats_' . $userId
        );
    }
}