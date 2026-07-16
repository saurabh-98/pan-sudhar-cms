<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentRequest;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class WalletController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WALLET LIST
    |--------------------------------------------------------------------------
    */

    public function index()
    {
       
        return view('admin.wallet.index');
    }


    public function retailerList()
    {
        $retailers = User::role('retailer')->latest();

        return DataTables::of($retailers)

            ->addIndexColumn()

            ->editColumn('wallet_balance', function ($row) {
                return '<strong class="text-success">₹'.number_format($row->wallet_balance, 2).'</strong>';
            })

            ->addColumn('action', function ($row) {
                return view('admin.wallet.partials.action-buttons', [
                    'user'   => $row,
                    'addBtn' => 'Add',
                    'addClass' => 'btn-primary',
                ])->render();
            })

            ->rawColumns(['wallet_balance', 'action'])

            ->make(true);
    }


    public function executiveList()
    {
        $executives = User::role('Executive')->latest();

        return DataTables::of($executives)

            ->addIndexColumn()

            ->editColumn('wallet_balance', function ($row) {
                return '<strong class="text-success">₹'.number_format($row->wallet_balance, 2).'</strong>';
            })

            ->addColumn('action', function ($row) {
                return view('admin.wallet.partials.action-buttons', [
                    'user'   => $row,
                    'addBtn' => 'Recharge',
                    'addClass' => 'btn-success',
                ])->render();
            })

            ->rawColumns(['wallet_balance', 'action'])

            ->make(true);
    }

   
    
    
    public function addBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = DB::transaction(function () use ($request, $id) {

            $user = User::lockForUpdate()->findOrFail($id);

            $user->increment('wallet_balance', $request->amount);

            $user->refresh(); 

            $remark = $user->hasRole('Executive')
                ? 'Executive Recharge By Admin'
                : 'Wallet Recharge By Admin';

            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'type' => 'credit',
                'remark' => $remark,
            ]);

            return $user;
        });

        return response()->json([
            'success' => true,
            'message' => $user->hasRole('Executive')
                ? 'Executive wallet balance added successfully.'
                : 'Retailer wallet balance added successfully.',
            'data' => [
                'user_id' => $user->id,
                'wallet_balance' => $user->wallet_balance,
            ]
        ], 200);
    }

    public function deductBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = User::findOrFail($id);

        if ($user->wallet_balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance.'
            ], 422);
        }

        $user->decrement('wallet_balance', $request->amount);

        WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'debit',
            'remark' => 'Wallet Deducted By Admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wallet balance deducted successfully.',
            'wallet_balance' => $user->fresh()->wallet_balance,
        ]);
    }
   
    public function transactions()
    {
        $transactions = WalletTransaction::with('user')
            ->latest()
            ->paginate(30);

        return view(
            'admin.wallet.transactions',
            compact('transactions')
        );
    }

    public function paymentRequests()
    {
        if (request()->ajax()) {

            $payments = PaymentRequest::with('retailer')
                ->latest();

            return DataTables::of($payments)

                ->addIndexColumn()

                ->addColumn('retailer', function ($row) {

                    return '
                        <strong>'.$row->retailer->name.'</strong><br>
                        <small>'.$row->retailer->mobile.'</small>
                    ';

                })

                ->editColumn('amount', function ($row) {

                    return '
                        <span class="text-success fw-bold">
                            ₹'.number_format($row->amount,2).'
                        </span>
                    ';

                })

                ->editColumn('status', function ($row) {

                    if($row->status=='Approved'){

                        return '<span class="badge bg-success">Approved</span>';

                    }

                    if($row->status=='pending'){

                        return '<span class="badge bg-warning">Pending</span>';

                    }

                    return '<span class="badge bg-danger">Rejected</span>';

                })

                ->editColumn('created_at', function ($row) {

                    return $row->created_at->format('d M Y h:i A');

                })

                ->addColumn('action', function ($row) {

                    return '
                        <a href="'.route('admin.wallet.payment-request.show',$row->id).'"
                        class="btn btn-sm btn-primary">

                            <i class="fas fa-eye"></i>

                        </a>
                    ';

                })

                ->rawColumns([
                    'retailer',
                    'amount',
                    'status',
                    'action'
                ])

                ->make(true);

        }

        return view('admin.wallet.payment-request.index');
    }


    public function showPaymentRequest($id)
    {
        $payment = PaymentRequest::with('retailer')
            ->findOrFail($id);

        return view(
            'admin.wallet.payment-request.show',
            compact('payment')
        );
    }

    public function approvePayment(Request $request,$id)
    {
        DB::beginTransaction();

        try{

            $payment = PaymentRequest::findOrFail($id);

            if($payment->status!='pending'){

                return back()->with(
                    'error',
                    'Request already processed.'
                );

            }

            $user = User::findOrFail($payment->retailer_id);

            $user->wallet_balance += $payment->amount;

            $user->save();

            WalletTransaction::create([

                'user_id'=>$user->id,

                'amount'=>$payment->amount,

                'type'=>'credit',

                'remark'=>'Wallet Recharge Approved'

            ]);

            $payment->update([

                'status'=>'approved',

                'admin_remarks'=>$request->admin_remarks,

                'verified_at'=>Carbon::now()

            ]);

            DB::commit();

            return redirect()
                ->route('admin.wallet.payment-requests')
                ->with(
                    'success',
                    'Payment approved successfully.'
                );

        }catch(\Exception $e){

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );

        }
    }

    public function rejectPayment(Request $request,$id)
    {
        $payment = PaymentRequest::findOrFail($id);

        if($payment->status!='pending'){

            return back()->with(
                'error',
                'Request already processed.'
            );

        }

        $payment->update([

            'status'=>'rejected',

            'admin_remarks'=>$request->admin_remarks,

            'verified_at'=>Carbon::now()

        ]);

        return redirect()
            ->route('admin.wallet.payment-requests')
            ->with(
                'success',
                'Payment rejected successfully.'
            );
    }
}