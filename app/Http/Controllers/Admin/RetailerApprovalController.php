<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class RetailerApprovalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        if ($request->ajax())
        {

            $retailers = DB::table('retailers')

                ->leftJoin(
                    'states',
                    'states.id',
                    '=',
                    'retailers.state_id'
                )

                ->leftJoin(
                    'districts',
                    'districts.id',
                    '=',
                    'retailers.district_id'
                )

                ->select(

                    'retailers.*',

                    'states.name as state_name',

                    'districts.name as district_name'

                )

                ->latest('retailers.id');

            return DataTables::of($retailers)

                ->addIndexColumn()

                ->addColumn('shop_name', function ($row) {

                    return '

                        <div class="retailer-box">

                            <div class="retailer-avatar">

                                '

                                . strtoupper(
                                    substr(
                                        $row->shop_name ?? 'R',
                                        0,
                                        1
                                    )
                                )

                                . '

                            </div>

                            <div>

                                <div class="retailer-name">

                                    '

                                    . ($row->shop_name ?? 'N/A')

                                    . '

                                </div>

                            </div>

                        </div>

                    ';
                })

                ->addColumn('owner_name', function ($row) {

                    return '

                        <div class="applicant-box">

                            '

                            . ($row->name ?? 'N/A')

                            . '

                        </div>

                    ';
                })

                ->addColumn('mobile', function ($row) {

                    return $row->mobile;
                })

                ->addColumn('email', function ($row) {

                    return $row->email;
                })

                ->addColumn('state', function ($row) {

                    return $row->state_name ?? 'N/A';
                })

                ->addColumn('district', function ($row) {

                    return $row->district_name ?? 'N/A';
                })

                ->addColumn('status', function ($row) {

                    if ($row->status === 'approved')
                    {

                        return '

                            <span class="badge bg-success">

                                Approved

                            </span>

                        ';
                    }

                    if ($row->status === 'rejected')
                    {

                        return '

                            <span class="badge bg-danger">

                                Rejected

                            </span>

                        ';
                    }

                    return '

                        <span class="badge bg-warning text-dark">

                            Pending

                        </span>

                    ';
                })

                ->addColumn('dashboard_access', function ($row) {

                    if (
                        $row->status === 'approved'
                        &&
                        $row->user_id
                    ) {

                        return '

                            <a
                                href="'

                                . route(
                                    'admin.retailer-approvals.login-as',
                                    $row->user_id
                                )

                                . '"

                                class="btn btn-primary btn-sm">

                                <i class="fa fa-sign-in-alt"></i>

                                Dashboard

                            </a>

                        ';
                    }

                    return '

                        <span class="badge bg-secondary">

                            Pending

                        </span>

                    ';
                })

                ->addColumn('created_at', function ($row) {

                    return date(

                        'd M Y h:i A',

                        strtotime(
                            $row->created_at
                        )

                    );
                })

                ->addColumn('action', function ($row) {

                    $buttons = '

                        <div class="d-flex gap-2">

                    ';

                    if ($row->status === 'pending')
                    {

                        $buttons .= '

                            <form
                                method="POST"
                                action="'

                                . route(
                                    'admin.retailer-approvals.approve',
                                    $row->id
                                )

                                . '">

                                '

                                . csrf_field()

                                . '

                                <button
                                    type="submit"
                                    class="btn btn-success btn-sm">

                                    <i class="fa fa-check"></i>

                                    Approve

                                </button>

                            </form>

                        ';

                        $buttons .= '

                            <form
                                method="POST"
                                action="'

                                . route(
                                    'admin.retailer-approvals.reject',
                                    $row->id
                                )

                                . '">

                                '

                                . csrf_field()

                                . '

                                <input
                                    type="hidden"
                                    name="reason"
                                    value="Rejected By Admin">

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm">

                                    <i class="fa fa-times"></i>

                                    Reject

                                </button>

                            </form>

                        ';
                    }
                    else
                    {

                        $buttons .= '

                            <span class="badge bg-secondary">

                                Processed

                            </span>

                        ';
                    }

                    $buttons .= '

                        </div>

                    ';

                    return $buttons;
                })

                ->rawColumns([

                    'shop_name',

                    'owner_name',

                    'status',

                    'dashboard_access',

                    'action'

                ])

                ->make(true);
        }

        return view(
            'admin.retailer-approvals.index'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE RETAILER
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        $credentials = [];

        DB::transaction(function () use ($id, &$credentials) {

            $retailer = DB::table('retailers')
                ->where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$retailer) {

                abort(404);
            }

            if ($retailer->status === 'approved') {

                return;
            }

            $existingUser = User::where(
                'email',
                $retailer->email
            )
            ->orWhere(
                'mobile',
                $retailer->mobile
            )
            ->first();

            if ($existingUser) {

                throw new \Exception(
                    'Retailer account already exists.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | LOGIN CREDENTIALS
            |--------------------------------------------------------------------------
            */

            $userId = $retailer->email;

            $plainPassword = $retailer->mobile;

            $user = User::create([

                'name' => $retailer->name,

                'email' => $userId,

                'mobile' => $retailer->mobile,

                'password' => Hash::make(
                    $plainPassword
                ),

                'status' => 1

            ]);

            $user->assignRole(
                'retailer'
            );

            DB::table('retailers')
                ->where('id', $id)
                ->update([

                    'user_id'      => $user->id,

                    'status'       => 'approved',

                    'is_verified'  => 1,

                    'approved_by'  => auth()->id(),

                    'approved_at'  => now(),

                    'updated_at'   => now()

                ]);

            /*
            |--------------------------------------------------------------------------
            | STORE CREDENTIALS FOR BLADE
            |--------------------------------------------------------------------------
            */

            $credentials = [

                'username' => $userId,

                'password' => $plainPassword

            ];
        });

        return back()

            ->with(

                'success',

                'Retailer approved successfully.'

            )

            ->with(

                'credentials',

                $credentials

            );
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT RETAILER
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        $id
    )
    {
        DB::table('retailers')
            ->where('id', $id)
            ->update([

                'status' => 'rejected',

                'rejected_by' => auth()->id(),

                'rejected_at' => now(),

                'rejection_reason' => $request->reason,

                'updated_at' => now()

            ]);

        return back()->with(

            'success',

            'Retailer rejected successfully.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN AS RETAILER
    |--------------------------------------------------------------------------
    */

    public function loginAsRetailer($userId)
{
    $retailer = User::findOrFail($userId);

    if (!$retailer->hasRole('retailer')) {
        abort(403);
    }

    $adminId = auth()->id();

    Auth::login($retailer);

    request()->session()->regenerate();

    session([
        'admin_id' => $adminId
    ]);

    return redirect()->route(
        'retailer.dashboard'
    );
}
    /*
    |--------------------------------------------------------------------------
    | BACK TO ADMIN
    |--------------------------------------------------------------------------
    */

    public function backToAdmin()
    {
        $adminId = session('admin_id');

        if (!$adminId) {
            abort(403, 'Admin session not found.');
        }

        session()->forget('admin_id');

        Auth::logout();

        Auth::loginUsingId($adminId);

        request()->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }
}