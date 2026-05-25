<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WALLET LIST
    |--------------------------------------------------------------------------
    */

    public function index()
{
    $retailers = User::role('retailer')
        ->latest()
        ->paginate(20);
       

    return view(
        'admin.wallet.index',
        compact('retailers')
    );
}
    /*
    |--------------------------------------------------------------------------
    | ADD BALANCE
    |--------------------------------------------------------------------------
    */

    public function addBalance(Request $request, $id)
    {
        $request->validate([

            'amount' => 'required|numeric|min:1'

        ]);

        $user = User::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | ADD WALLET BALANCE
        |--------------------------------------------------------------------------
        */

        $user->wallet_balance += $request->amount;

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | SAVE TRANSACTION
        |--------------------------------------------------------------------------
        */

        WalletTransaction::create([

            'user_id' => $user->id,

            'amount' => $request->amount,

            'type' => 'credit',

            'remark' => 'Wallet Recharge By Admin'

        ]);

        return back()->with(
            'success',
            'Wallet balance added successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSACTIONS
    |--------------------------------------------------------------------------
    */

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
}