<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\PaymentRequest;
use App\Models\UpiSetting;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Yajra\DataTables\Facades\DataTables;

class WalletController extends Controller
{

   

    public function __construct()
    {
       
    }


    public function recharge()
    {
        $upi = UpiSetting::where('is_active', 1)->first();

        return view('retailer.wallet.recharge', compact('upi'));
    }



    public function generateQr(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10'
        ]);

        $upi = UpiSetting::where('is_active', 1)->first();

        if (!$upi) {
            return response()->json([
                'success' => false,
                'message' => 'No active UPI found.'
            ]);
        }

        $upiUrl = sprintf(
            'upi://pay?pa=%s&pn=%s&am=%s&cu=INR',
            $upi->upi_id,
            urlencode($upi->name),
            $request->amount
        );

        $qrCode = new QrCode(
            data: $upiUrl,
            size: 300,
            margin: 10
        );

        $writer = new PngWriter();

        $result = $writer->write($qrCode);

        return response()->json([
            'success' => true,

            'upi' => [
                'upi_id' => $upi->upi_id,
                'name'   => $upi->name,
            ],

            'qr' => 'data:image/png;base64,' . base64_encode($result->getString())
        ]);
    }
   

    public function submitPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'utr' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:500',
            'screenshot' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        DB::beginTransaction();

        try {

            $upi = UpiSetting::where('is_active', 1)->first();

            if (!$upi) {
                return back()->with('error', 'No active UPI setting found.');
            }

            $screenshot = null;

            if ($request->hasFile('screenshot')) {

                $file = $request->file('screenshot');

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('uploads/payment-screenshots'), $filename);

                $screenshot = 'uploads/payment-screenshots/' . $filename;
            }

            PaymentRequest::create([
                'retailer_id'   => auth()->id(),
                'amount'        => $request->amount,
                'upi_id'        => $upi->upi_id,
                'merchant_name' => $upi->name,
                'utr'           => $request->utr,
                'screenshot'    => $screenshot,
                'remarks'       => $request->remarks,
                'status'        => 'pending',
            ]);

            DB::commit();

            return redirect()
                ->route('retailer.wallet.recharge-history')
                ->with('success', 'Payment request submitted successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }


    public function rechargeHistory()
    {
        if (request()->ajax()) {

            $payments = PaymentRequest::where(
                'retailer_id',
                auth()->id()
            )->latest();

            return DataTables::of($payments)

                ->addIndexColumn()

                /*
                |--------------------------------------------------------------------------
                | Amount
                |--------------------------------------------------------------------------
                */

                ->editColumn('amount', function ($row) {

                    return '
                        <span class="amount">
                            ₹' . number_format($row->amount, 2) . '
                        </span>
                    ';

                })

                /*
                |--------------------------------------------------------------------------
                | UTR
                |--------------------------------------------------------------------------
                */

                ->editColumn('utr', function ($row) {

                    return $row->utr
                        ? '<span class="fw-semibold">' . $row->utr . '</span>'
                        : '<span class="text-muted">N/A</span>';

                })

                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                ->editColumn('status', function ($row) {

                    if ($row->status == 'approved') {

                        return '
                            <span class="status status-approved">
                                <i class="fas fa-check-circle me-1"></i>
                                Approved
                            </span>
                        ';
                    }

                    if ($row->status == 'pending') {

                        return '
                            <span class="status status-pending">
                                <i class="fas fa-clock me-1"></i>
                                Pending
                            </span>
                        ';
                    }

                    return '
                        <span class="status status-rejected">
                            <i class="fas fa-times-circle me-1"></i>
                            Rejected
                        </span>
                    ';

                })

                /*
                |--------------------------------------------------------------------------
                | Submitted Date
                |--------------------------------------------------------------------------
                */

                ->editColumn('created_at', function ($row) {

                    return '
                        <div class="text-center">

                            <div class="fw-semibold">

                                ' . $row->created_at->format('d M Y') . '

                            </div>

                            <small class="text-muted">

                                ' . $row->created_at->format('h:i A') . '

                            </small>

                        </div>
                    ';

                })

                /*
                |--------------------------------------------------------------------------
                | Action
                |--------------------------------------------------------------------------
                */

                ->addColumn('action', function ($row) {

                    return '
                        <a href="' . route('retailer.wallet.recharge.show', $row->id) . '"
                        class="btn btn-view"
                        title="View Details">

                            <i class="fas fa-eye"></i>

                        </a>
                    ';

                })

                ->rawColumns([
                    'amount',
                    'utr',
                    'status',
                    'created_at',
                    'action'
                ])

                ->make(true);
        }

        return view('retailer.wallet.recharge-history');
    }

    
    public function showRecharge($id)
    {
        $payment = PaymentRequest::where(
            'retailer_id',
            auth()->id()
        )->findOrFail($id);

        return view(
            'retailer.wallet.show',
            compact('payment')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | WALLET HISTORY
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        /*
        |--------------------------------------------------------------------------
        | AJAX DATATABLE
        |--------------------------------------------------------------------------
        */

        if (request()->ajax()) {

            $transactions = WalletTransaction::query()

                ->where(
                    'user_id',
                    auth()->id()
                )

                ->latest();

            return DataTables::of($transactions)

                ->addIndexColumn()

                /*
                |--------------------------------------------------------------------------
                | TRANSACTION ID
                |--------------------------------------------------------------------------
                */

                ->addColumn('transaction_id', function ($row) {

                    return '

                        <span class="wallet-transaction-id">

                            #'.$row->id.'

                        </span>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | AMOUNT
                |--------------------------------------------------------------------------
                */

                ->editColumn('amount', function ($row) {

                    return '

                        <span class="wallet-amount">

                            ₹'.number_format($row->amount, 2).'

                        </span>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | TYPE
                |--------------------------------------------------------------------------
                */

                ->editColumn('type', function ($row) {

                    if ($row->type === 'credit') {

                        return '

                            <span class="badge wallet-credit-badge">

                                <i class="fas fa-arrow-down me-1"></i>

                                Credit

                            </span>

                        ';
                    }

                    return '

                        <span class="badge wallet-debit-badge">

                            <i class="fas fa-arrow-up me-1"></i>

                            Debit

                        </span>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | REMARK
                |--------------------------------------------------------------------------
                */

                ->editColumn('remark', function ($row) {

                    return '

                        <div class="wallet-remark">

                            '.$row->remark.'

                        </div>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | CREATED DATE
                |--------------------------------------------------------------------------
                */

                ->editColumn('created_at', function ($row) {

                    return '

                        <div class="wallet-date-wrap">

                            <div class="wallet-date">

                                '.$row->created_at->format('d M Y').'

                            </div>

                            <small class="wallet-time">

                                '.$row->created_at->format('h:i A').'

                            </small>

                        </div>

                    ';
                })

                /*
                |--------------------------------------------------------------------------
                | RAW COLUMNS
                |--------------------------------------------------------------------------
                */

                ->rawColumns([

                    'transaction_id',

                    'amount',

                    'type',

                    'remark',

                    'created_at'

                ])

                ->make(true);
        }

        /*
        |--------------------------------------------------------------------------
        | NORMAL VIEW
        |--------------------------------------------------------------------------
        */

        return view('retailer.wallet.history');
    }
}