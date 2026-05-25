<?php

namespace App\Repositories;

use App\Models\PanApplication;
use App\Models\ItrFile;
use App\Models\ServiceDocument;
use App\Models\WalletTransaction;

class DashboardRepository
{

    /*
    |--------------------------------------------------------------------------
    | TOTAL PAN APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function getTotalPanApplications()
    {

        return PanApplication::query()

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL ITR APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function getTotalItrApplications()
    {

        return ItrFile::query()

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGNED APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function getAssignedApplications()
    {

        $panAssigned = PanApplication::query()

            ->whereNotNull('assigned_to')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

        $itrAssigned = ItrFile::query()

            ->whereNotNull('assigned_to')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

        return $panAssigned + $itrAssigned;

    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETED APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function getCompletedApplications()
    {

        $panCompleted = PanApplication::query()

            ->where('status', 'Approved')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

        $itrCompleted = ItrFile::query()

            ->where('status', 'Approved')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

        return $panCompleted + $itrCompleted;

    }


    /*
    |--------------------------------------------------------------------------
    | FRESH APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function getFreshApplications()
    {

        if(auth()->user()->hasRole('Executive'))
        {

            return PanApplication::query()

                ->where(

                    'assigned_to',

                    auth()->id()

                )

                ->where('status', 'Pending')

                ->count()

                +

                ItrFile::query()

                ->where(

                    'assigned_to',

                    auth()->id()

                )

                ->where('status', 'pending')

                ->count();

        }

        return PanApplication::query()

            ->whereNull('assigned_to')

            ->count()

            +

            ItrFile::query()

            ->whereNull('assigned_to')

            ->count();

    }


    /*
    |--------------------------------------------------------------------------
    | PROCESSING APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function getProcessingApplications()
    {

        return PanApplication::query()

            ->where('status', 'Processing')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count()

            +

            ItrFile::query()

            ->where('status', 'processing')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

    }


    /*
    |--------------------------------------------------------------------------
    | PENDING APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function getPendingApplications()
    {

        return PanApplication::query()

            ->where('status', 'Pending')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count()

            +

            ItrFile::query()

            ->where('status', 'pending')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

    }


    /*
    |--------------------------------------------------------------------------
    | APPROVED APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function getApprovedApplications()
    {

        return PanApplication::query()

            ->where('status', 'Approved')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count()

            +

            ItrFile::query()

            ->where('status', 'approved')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

    }


    /*
    |--------------------------------------------------------------------------
    | REJECTED APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function getRejectedApplications()
    {

        return PanApplication::query()

            ->where('status', 'Rejected')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count()

            +

            ItrFile::query()

            ->where('status', 'rejected')

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'assigned_to',

                        auth()->id()

                    );

                }

            )

            ->count();

    }


    /*
    |--------------------------------------------------------------------------
    | TODAY UPLOADS
    |--------------------------------------------------------------------------
    */

    public function getTodayUploads()
    {

        return ServiceDocument::query()

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'user_id',

                        auth()->id()

                    );

                }

            )

            ->whereDate(

                'created_at',

                today()

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

        return WalletTransaction::query()

            ->when(

                auth()->user()->hasRole('Executive'),

                function($query){

                    $query->where(

                        'user_id',

                        auth()->id()

                    )

                    ->where(

                        'type',

                        'credit'

                    );

                }

            )

            ->sum('amount');

    }


    /*
    |--------------------------------------------------------------------------
    | MONTHS
    |--------------------------------------------------------------------------
    */

    public function getMonths()
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
            'Dec'

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

                $panCount = PanApplication::query()

                    ->whereMonth(

                        'created_at',

                        $month

                    )

                    ->when(

                        auth()->user()->hasRole('Executive'),

                        function($query){

                            $query->where(

                                'assigned_to',

                                auth()->id()

                            );

                        }

                    )

                    ->count();

                $itrCount = ItrFile::query()

                    ->whereMonth(

                        'created_at',

                        $month

                    )

                    ->when(

                        auth()->user()->hasRole('Executive'),

                        function($query){

                            $query->where(

                                'assigned_to',

                                auth()->id()

                            );

                        }

                    )

                    ->count();

                return $panCount + $itrCount;

            });

    }

}