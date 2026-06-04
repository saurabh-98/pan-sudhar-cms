<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Module;
use App\Models\RetailerModuleAccess;

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

                    <button
                        type="button"
                        class="btn btn-success btn-sm approve-btn"
                        data-id="'.$row->id.'">

                        <i class="fa fa-check"></i>

                        Assign Modules & Approve

                    </button>

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

                    <a
                        href="'

                        . route(
                            'admin.retailer-approvals.modules',
                            $row->user_id
                        )

                        . '"

                        class="btn btn-warning btn-sm">

                        <i class="fa fa-lock"></i>

                        Manage Modules

                    </a>

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

            'module_access',

            'action'

        ])

        ->make(true);
}

$modules = Module::where(
    'status',
    1
)
->orderBy('name')
->get();

return view(
    'admin.retailer-approvals.index',
    compact(
        'modules'
    )
);


}

    /*
    |--------------------------------------------------------------------------
    | APPROVE RETAILER
    |--------------------------------------------------------------------------
    */

        public function approve(
Request $request,
$id
)
{
$request->validate([

    'modules' => [
        'required',
        'array',
        'min:1'
    ],

    'modules.*' => [
        'exists:modules,id'
    ]

]);

$credentials = [];

DB::transaction(function () use (
    $id,
    $request,
    &$credentials
) {

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

    /*
    |--------------------------------------------------------------------------
    | ASSIGN ROLE
    |--------------------------------------------------------------------------
    */

    $user->assignRole(
        'retailer'
    );

    /*
    |--------------------------------------------------------------------------
    | ASSIGN SELECTED MODULE ACCESS
    |--------------------------------------------------------------------------
    */

    foreach (
        $request->modules
        as $moduleId
    ) {

        RetailerModuleAccess::firstOrCreate([

            'retailer_id' => $user->id,

            'module_id'   => $moduleId,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE RETAILER STATUS
    |--------------------------------------------------------------------------
    */

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
    | STORE CREDENTIALS
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

    public function modules($userId)
{
$user = User::findOrFail(
$userId
);


$modules = Module::where(
    'status',
    1
)
->orderBy(
    'name'
)
->get();

$assignedModules = RetailerModuleAccess::where(
        'retailer_id',
        $userId
    )
    ->pluck(
        'module_id'
    )
    ->toArray();

return view(
    'admin.retailer-approvals.module',
    compact(
        'user',
        'modules',
        'assignedModules'
    )
);


}

 /*                                                                         |
| -------------------------------------------------------------------------- |
| UPDATE RETAILER MODULES                                                    |
| -------------------------------------------------------------------------- |
| */                                                                         

public function updateModules(
    Request $request,
    $userId
)
{
    $request->validate([

        'modules' => [
            'required',
            'array',
            'min:1'
        ],

        'modules.*' => [
            'exists:modules,id'
        ]

    ]);

    DB::transaction(function () use (
        $request,
        $userId
    ) {

        RetailerModuleAccess::where(
            'retailer_id',
            $userId
        )->delete();

        foreach (
            $request->modules
            as $moduleId
        ) {

            RetailerModuleAccess::create([

                'retailer_id' => $userId,

                'module_id'   => $moduleId

            ]);
        }

    });

    return response()->json([

        'success' => true,

        'message' => 'Retailer modules updated successfully.',

        'redirect' => route(
            'admin.retailer-approvals.index'
        )

    ]);
}

public function getModules($userId)
{
    $modules = RetailerModuleAccess::query()

        ->join(
            'modules',
            'modules.id',
            '=',
            'retailer_module_access.module_id'
        )

        ->where(
            'retailer_module_access.retailer_id',
            $userId
        )

        ->select(
            'modules.name'
        )

        ->pluck('name');

    return response()->json(
        $modules
    );
}

}