<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use Yajra\DataTables\Facades\DataTables;

class WalletController extends Controller
{
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