<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Charge;
use Yajra\DataTables\Facades\DataTables;

class ChargeController extends Controller
{
    /**
     * Display Charges Page
     */
    public function index()
    {
        return view(
            'admin.charges.index'
        );
    }

  
   
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $charges = Charge::with('commissions')
                ->select([
                    'id',
                    'name',
                    'code',
                    'type',
                    'value',
                    'is_active',
                    'created_at'
                ])
                ->latest();

            return DataTables::of($charges)

                ->addIndexColumn()

                ->editColumn('type', function ($row) {

                    return ucfirst($row->type);

                })

                ->editColumn('value', function ($row) {

                    if ($row->type === 'percentage') {
                        return $row->value . '%';
                    }

                    return number_format($row->value, 2);

                })

                ->addColumn('distributor_commission', function ($row) {

                    $commission = $row->commissions
                        ->where('role', 'Distributor')
                        ->first();

                    if (!$commission) {
                        return '0.00';
                    }

                    return $commission->type === 'percentage'
                        ? $commission->value . '%'
                        : number_format($commission->value, 2);

                })

                ->addColumn('executive_commission', function ($row) {

                    $commission = $row->commissions
                        ->where('role', 'Executive')
                        ->first();

                    if (!$commission) {
                        return '0.00';
                    }

                    return $commission->type === 'percentage'
                        ? $commission->value . '%'
                        : number_format($commission->value, 2);

                })

                ->addColumn('status', function ($row) {

                    return $row->is_active

                        ? '<span class="chx-status chx-active">
                                Active
                        </span>'

                        : '<span class="chx-status chx-inactive">
                                Inactive
                        </span>';

                })

                ->addColumn('action', function ($row) {

                    return '

                        <div class="d-flex align-items-center gap-2">

                            <button
                                type="button"
                                class="btn btn-warning btn-sm editCharge"
                                data-id="'.$row->id.'"
                                title="Edit Charge"
                            >
                                <i class="fa fa-edit"></i>
                            </button>

                            <button
                                type="button"
                                class="btn btn-danger btn-sm deleteCharge"
                                data-id="'.$row->id.'"
                                title="Delete Charge"
                            >
                                <i class="fa fa-trash"></i>
                            </button>

                        </div>

                    ';

                })

                ->rawColumns([
                    'status',
                    'action'
                ])

                ->make(true);
        }

        abort(404);
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'max:255'
            ],

            'code' => [
                'required',
                'max:255',
                'unique:charges,code'
            ],

            'type' => [
                'required',
                'in:fixed,percentage'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'distributor_commission' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'distributor_type' => [
                'required',
                'in:fixed,percentage'
            ],

            'executive_commission' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'executive_type' => [
                'required',
                'in:fixed,percentage'
            ],

            'description' => [
                'nullable'
            ],

            'status' => [
                'required',
                'in:0,1'
            ]
        ]);

        $charge = Charge::create([

            'name'        => $request->name,
            'code'        => $request->code,
            'type'        => $request->type,
            'value'       => $request->amount,
            'description' => $request->description,
            'is_active'   => $request->status,

        ]);

        $charge->commissions()->create([

            'role'      => 'Distributor',
            'type'      => $request->distributor_type,
            'value'     => $request->distributor_commission ?? 0,
            'is_active' => true,

        ]);

        $charge->commissions()->create([

            'role'      => 'Executive',
            'type'      => $request->executive_type,
            'value'     => $request->executive_commission ?? 0,
            'is_active' => true,

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Charge created successfully.',

            'data' => $charge->load('commissions')

        ]);
    }

    
    public function edit($id)
    {
        $charge = Charge::with('commissions')->findOrFail($id);

        $distributor = $charge->commissions
            ->where('role', 'Distributor')
            ->first();

        $executive = $charge->commissions
            ->where('role', 'Executive')
            ->first();

        return response()->json([

            'id' => $charge->id,

            'name' => $charge->name,

            'code' => $charge->code,

            'type' => $charge->type,

            'amount' => $charge->value,

            'description' => $charge->description,

            'status' => $charge->is_active,

            'distributor_commission' => $distributor?->value ?? 0,

            'distributor_type' => $distributor?->type ?? 'fixed',

            'executive_commission' => $executive?->value ?? 0,

            'executive_type' => $executive?->type ?? 'fixed',

        ]);
    }


   
    public function update(Request $request, $id)
    {
        $charge = Charge::findOrFail($id);

        $validated = $request->validate([

            'name' => [
                'required',
                'max:255'
            ],

            'code' => [
                'required',
                'max:255',
                'unique:charges,code,' . $id
            ],

            'type' => [
                'required',
                'in:fixed,percentage'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'distributor_commission' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'distributor_type' => [
                'required',
                'in:fixed,percentage'
            ],

            'executive_commission' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'executive_type' => [
                'required',
                'in:fixed,percentage'
            ],

            'description' => [
                'nullable'
            ],

            'status' => [
                'required',
                'in:0,1'
            ]

        ]);

        $charge->update([

            'name'        => $request->name,
            'code'        => $request->code,
            'type'        => $request->type,
            'value'       => $request->amount,
            'description' => $request->description,
            'is_active'   => $request->status,

        ]);

        // Distributor Commission
        $charge->commissions()->updateOrCreate(

            [
                'role' => 'Distributor'
            ],

            [
                'type'      => $request->distributor_type,
                'value'     => $request->distributor_commission ?? 0,
                'is_active' => true,
            ]

        );

        // Executive Commission
        $charge->commissions()->updateOrCreate(

            [
                'role' => 'Executive'
            ],

            [
                'type'      => $request->executive_type,
                'value'     => $request->executive_commission ?? 0,
                'is_active' => true,
            ]

        );

        return response()->json([

            'success' => true,

            'message' => 'Charge updated successfully.',

            'data' => $charge->load('commissions')

        ]);
    }

   
    public function show($id)
    {
        $charge = Charge::with('commissions')->findOrFail($id);

        $distributor = $charge->commissions
            ->where('role', 'Distributor')
            ->first();

        $executive = $charge->commissions
            ->where('role', 'Executive')
            ->first();

        return response()->json([

            'success' => true,

            'data' => [

                'id' => $charge->id,

                'name' => $charge->name,

                'code' => $charge->code,

                'type' => $charge->type,

                'amount' => $charge->value,

                'description' => $charge->description,

                'status' => $charge->is_active,

                'distributor_commission' => $distributor?->value ?? 0,

                'distributor_type' => $distributor?->type ?? 'fixed',

                'executive_commission' => $executive?->value ?? 0,

                'executive_type' => $executive?->type ?? 'fixed',

            ]

        ]);
    }

  
    public function destroy($id)
    {
        $charge = Charge::findOrFail($id);

        $charge->delete();

        return response()->json([

            'success' => true,

            'message' => 'Charge deleted successfully.'

        ]);
    }
}

