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
                return '<strong class="text-success">₹' . number_format($row->wallet_balance, 2) . '</strong>';
            })

            ->addColumn('wallet_due', function ($row) {

                if ($row->wallet_due > 0) {
                    return '<strong class="text-danger">₹' . number_format($row->wallet_due, 2) . '</strong>';
                }

                return '<strong class="text-success">₹0.00</strong>';
            })

            ->addColumn('action', function ($row) {
                return view('admin.wallet.partials.action-buttons', [
                    'user'      => $row,
                    'addBtn'    => 'Add',
                    'addClass'  => 'btn-primary',
                ])->render();
            })

            ->rawColumns([
                'wallet_balance',
                'wallet_due',
                'action'
            ])

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
            'recharge_type' => 'required|in:payment,credit',
        ]);

        $user = DB::transaction(function () use ($request, $id) {

            $user = User::lockForUpdate()->findOrFail($id);

            // Increase wallet balance
            $user->wallet_balance += $request->amount;

            // If recharge is on credit, also increase due amount
            if ($request->recharge_type === 'credit') {
                $user->wallet_due += $request->amount;
            }

            $user->save();
            $user->refresh();

            // Wallet Transaction
            $remark = '';

            if ($user->hasRole('Executive')) {

                $remark = $request->recharge_type === 'credit'
                    ? 'Executive Credit Recharge (Pay Later)'
                    : 'Executive Wallet Recharge (Payment Received)';

            } else {

                $remark = $request->recharge_type === 'credit'
                    ? 'Retailer Credit Recharge (Pay Later)'
                    : 'Retailer Wallet Recharge (Payment Received)';
            }

            WalletTransaction::create([
                'user_id' => $user->id,
                'amount'  => $request->amount,
                'type'    => 'credit',
                'remark'  => $remark,
            ]);

            return $user;
        });

        return response()->json([
            'success' => true,
            'message' => $request->recharge_type === 'credit'
                ? 'Wallet credited successfully. Due amount has also been updated.'
                : 'Wallet recharged successfully.',
            'data' => [
                'user_id'        => $user->id,
                'wallet_balance' => $user->wallet_balance,
                'wallet_due'     => $user->wallet_due,
            ]
        ]);
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

                    if($row->status=='approved'){

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

    public function approvePayment(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $payment = PaymentRequest::findOrFail($id);

            if ($payment->status != 'pending') {

                return response()->json([
                    'success' => false,
                    'message' => 'Request already processed.'
                ], 422);
            }

            $user = User::lockForUpdate()->findOrFail($payment->retailer_id);

            $paymentAmount = $payment->amount;

            /*
            |--------------------------------------------------------------------------
            | First adjust outstanding due
            |--------------------------------------------------------------------------
            */

            if ($user->wallet_due > 0) {

                if ($paymentAmount >= $user->wallet_due) {

                    // Payment clears all due
                    $remaining   = $paymentAmount - $user->wallet_due;
                    $adjustedDue = $user->wallet_due;

                    $user->wallet_due = 0;

                    // Extra amount goes into wallet
                    if ($remaining > 0) {

                        $user->wallet_balance += $remaining;

                        WalletTransaction::create([
                            'user_id' => $user->id,
                            'amount'  => $remaining,
                            'type'    => 'credit',
                            'remark'  => 'Extra Payment Credited To Wallet'
                        ]);
                    }

                    WalletTransaction::create([
                        'user_id' => $user->id,
                        'amount'  => $adjustedDue,
                        'type'    => 'due_clear',
                        'remark'  => 'Outstanding Due Cleared'
                    ]);

                } else {

                    // Partial payment, reduce due only
                    $user->wallet_due -= $paymentAmount;

                    WalletTransaction::create([
                        'user_id' => $user->id,
                        'amount'  => $paymentAmount,
                        'type'    => 'due_clear',
                        'remark'  => 'Partial Due Payment'
                    ]);
                }

            } else {

                // No outstanding due, normal wallet recharge
                $user->wallet_balance += $paymentAmount;

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount'  => $paymentAmount,
                    'type'    => 'credit',
                    'remark'  => 'Wallet Recharge Approved'
                ]);
            }

            $user->save();

            $payment->update([
                'status'        => 'approved',
                'admin_remarks' => $request->admin_remarks,
                'verified_at'   => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment approved successfully.',
                'data' => [
                    'payment_id'      => $payment->id,
                    'retailer_id'     => $user->id,
                    'wallet_balance'  => $user->wallet_balance,
                    'wallet_due'      => $user->wallet_due,
                    'payment_status'  => $payment->status,
                ]
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function rejectPayment(Request $request, $id)
    {
        $request->validate([
            'admin_remarks' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {

            $payment = PaymentRequest::findOrFail($id);

            if ($payment->status != 'pending') {

                return response()->json([
                    'success' => false,
                    'message' => 'Request already processed.'
                ], 422);
            }

            $payment->update([
                'status'        => 'rejected',
                'admin_remarks' => $request->admin_remarks,
                'verified_at'   => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment rejected successfully.',
                'data' => [
                    'payment_id'     => $payment->id,
                    'payment_status' => $payment->status,
                    'admin_remarks'  => $payment->admin_remarks,
                ]
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);

        }
    }
}