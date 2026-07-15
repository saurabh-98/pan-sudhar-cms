<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RetailerSession;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;



class RetailerActivityController extends Controller
{
    public function index()
    {
        $retailers = User::role('retailer')
            ->with('latestRetailerSession')
            ->get();

        return view(
            'admin.retailer-activity.index',
            compact('retailers')
        );
    }

   

    public function datatable()
    {
        $today = today();

        $retailers = User::role('retailer')
            ->select('users.*')

            // Last Activity
            ->selectSub(
                RetailerSession::select('last_activity_at')
                    ->whereColumn('retailer_id', 'users.id')
                    ->latest('last_activity_at')
                    ->limit(1),
                'last_activity_at'
            )

            // Logout Time
            ->selectSub(
                RetailerSession::select('logout_at')
                    ->whereColumn('retailer_id', 'users.id')
                    ->latest('last_activity_at')
                    ->limit(1),
                'logout_at'
            )

            // Today's Working Seconds
            ->selectSub(
                RetailerSession::selectRaw('COALESCE(SUM(duration_seconds),0)')
                    ->whereColumn('retailer_id', 'users.id')
                    ->whereDate('login_at', $today),
                'today_seconds'
            )

            // Total Working Seconds
            ->selectSub(
                RetailerSession::selectRaw('COALESCE(SUM(duration_seconds),0)')
                    ->whereColumn('retailer_id', 'users.id'),
                'total_seconds'
            )

            // Active Days
            ->selectSub(
                RetailerSession::selectRaw('COUNT(DISTINCT DATE(login_at))')
                    ->whereColumn('retailer_id', 'users.id'),
                'active_days'
            );

        return DataTables::eloquent($retailers)

            ->addIndexColumn()

            ->editColumn('name', function ($row) {

                $letter = strtoupper(substr($row->name, 0, 1));

                return '
                    <div class="d-flex align-items-center">

                        <div class="avatar-circle me-2">
                            '.$letter.'
                        </div>

                        <div>

                            <div class="fw-bold">
                                '.$row->name.'
                            </div>

                            <small class="text-muted">
                                '.$row->email.'
                            </small>

                        </div>

                    </div>';
            })

            ->editColumn('mobile', function ($row) {

                return $row->mobile ?: '-';

            })

            ->addColumn('status', function ($row) {

                if (
                    is_null($row->logout_at) &&
                    $row->last_activity_at &&
                    now()->diffInMinutes($row->last_activity_at) <= 5
                ) {

                    return '<span class="badge bg-success">🟢 Online</span>';
                }

                return '<span class="badge bg-danger">🔴 Offline</span>';

            })

            ->editColumn('last_activity_at', function ($row) {

                if (!$row->last_activity_at) {

                    return '-';
                }

                return '
                    <div>
                        <strong>'
                        . \Carbon\Carbon::parse($row->last_activity_at)->format('d M Y') .
                    '</strong><br>

                        <small class="text-muted">'
                        . \Carbon\Carbon::parse($row->last_activity_at)->format('h:i A') .
                    '</small><br>

                        <small class="text-primary">'
                        . \Carbon\Carbon::parse($row->last_activity_at)->diffForHumans() .
                    '</small>
                    </div>';

            })

            ->addColumn('today_time', function ($row) {

                return gmdate('H:i:s', (int) $row->today_seconds);

            })

            ->addColumn('total_time', function ($row) {

                $hours = floor($row->total_seconds / 3600);

                $minutes = floor(($row->total_seconds % 3600) / 60);

                return "{$hours}h {$minutes}m";

            })

            ->editColumn('active_days', function ($row) {

                return '<span class="badge bg-info">'
                        .$row->active_days.
                        ' Days</span>';

            })

            ->rawColumns([
                'name',
                'status',
                'last_activity_at',
                'active_days'
            ])

            ->make(true);
    }
}
