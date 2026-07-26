<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfitPartner;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProfitPartnerController extends Controller
{
    /**
     * Display Profit Partner Page
     */
    public function index()
    {
        return view('admin.profit-partners.index');
    }

    /**
     * Datatable List
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $partners = ProfitPartner::latest();

            return DataTables::of($partners)

                ->addIndexColumn()

                ->editColumn('profit_percentage', function ($row) {
                    return number_format($row->profit_percentage, 2) . ' %';
                })

                ->editColumn('status', function ($row) {

                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';

                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at
                        ? $row->created_at->format('d M Y h:i A')
                        : '-';
                })

                ->addColumn('action', function ($row) {

                    return '
                        <button class="btn btn-sm btn-primary editPartner" data-id="'.$row->id.'">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-sm btn-danger deletePartner" data-id="'.$row->id.'">
                            <i class="fa fa-trash"></i>
                        </button>
                    ';
                })

                ->rawColumns([
                    'status',
                    'action'
                ])

                ->make(true);
        }
    }

    /**
     * Store Partner
     */
    public function store(Request $request)
    {
        $request->validate([
            'partner_name' => 'required|string|max:100',
            'profit_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ]);

        $total = ProfitPartner::sum('profit_percentage');

        if (($total + $request->profit_percentage) > 100) {

            return response()->json([
                'message' => 'Total profit percentage cannot exceed 100%.'
            ], 422);
        }

        ProfitPartner::create([
            'partner_name' => $request->partner_name,
            'profit_percentage' => $request->profit_percentage,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Partner created successfully.'
        ]);
    }

    /**
     * Edit Partner
     */
    public function edit($id)
    {
        return response()->json(
            ProfitPartner::findOrFail($id)
        );
    }

    /**
     * Update Partner
     */
    public function update(Request $request, $id)
    {
        $partner = ProfitPartner::findOrFail($id);

        $request->validate([
            'partner_name' => 'required|string|max:100',
            'profit_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ]);

        $total = ProfitPartner::where('id', '!=', $id)
            ->sum('profit_percentage');

        if (($total + $request->profit_percentage) > 100) {

            return response()->json([
                'message' => 'Total profit percentage cannot exceed 100%.'
            ], 422);
        }

        $partner->update([
            'partner_name' => $request->partner_name,
            'profit_percentage' => $request->profit_percentage,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Partner updated successfully.'
        ]);
    }

    /**
     * Delete Partner
     */
    public function destroy($id)
    {
        ProfitPartner::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Partner deleted successfully.'
        ]);
    }
}