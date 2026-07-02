<?php

namespace App\Http\Controllers\Retailer\Pan;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\PanFindHistory;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ServiceGuidelineService;

class PanFindController extends Controller
{
   
    public function __construct(

        protected ServiceGuidelineService $serviceGuidelineService

    ) {}

    public function create()
    {
        $charge = Charge::where('code', 'pan_find')
            ->where('is_active', 1)
            ->first();

        $guideline = $this->serviceGuidelineService
                        ->getActiveGuideline('pan-find');
                        
        return view(
            'retailer.pan-find.create',
            compact('charge','guideline')
        );
    }

    /**
     * ==========================================
     * Store
     * ==========================================
     */
    public function store(Request $request)
    {
        $request->validate([

            'aadhaar_number' => [

                'required',

                'digits:12',

            ],

        ]);

        try {

            DB::transaction(function () use ($request) {

                /*
                |--------------------------------------------------------------------------
                | Service Charge
                |--------------------------------------------------------------------------
                */

                $charge = Charge::where(

                    'code',

                    'pan_find'

                )->where(

                    'is_active',

                    1

                )->firstOrFail();

                $amount = (float) $charge->value;

                /*
                |--------------------------------------------------------------------------
                | Retailer
                |--------------------------------------------------------------------------
                */

                $retailer = User::lockForUpdate()->findOrFail(

                    auth()->id()

                );

                if ($retailer->wallet_balance < $amount) {

                    throw new \Exception(

                        'Insufficient wallet balance.'

                    );

                }

                /*
                |--------------------------------------------------------------------------
                | Admin
                |--------------------------------------------------------------------------
                */

                $admin = User::role('Admin')

                    ->lockForUpdate()

                    ->first();

                /*
                |--------------------------------------------------------------------------
                | Wallet Deduction
                |--------------------------------------------------------------------------
                */

                $retailerOpening = $retailer->wallet_balance;

                $retailer->wallet_balance -= $amount;

                $retailer->save();

                /*
                |--------------------------------------------------------------------------
                | Admin Wallet Credit
                |--------------------------------------------------------------------------
                */

                $adminOpening = 0;

                if ($admin) {

                    $adminOpening = $admin->wallet_balance;

                    $admin->wallet_balance += $amount;

                    $admin->save();

                }

                /*
                |--------------------------------------------------------------------------
                | PAN Find History
                |--------------------------------------------------------------------------
                */

                PanFindHistory::create([

                    'user_id'         => $retailer->id,

                    'aadhaar_number' => $request->aadhaar_number,

                    'amount'          => $amount,

                    'status'          => 'Completed',

                ]);

                /*
                |--------------------------------------------------------------------------
                | Retailer Wallet Transaction
                |--------------------------------------------------------------------------
                */

                WalletTransaction::create([

                    'user_id' => $retailer->id,

                    'transaction_no' => 'PF' . now()->format('YmdHis') . rand(100,999),

                    'type' => 'debit',

                    'transaction_type' => 'PAN FIND',

                    'amount' => $amount,

                    'opening_balance' => $retailerOpening,

                    'closing_balance' => $retailer->wallet_balance,

                    'remark' => 'PAN Find Charge',

                    'status' => 'Success',

                ]);

                /*
                |--------------------------------------------------------------------------
                | Admin Wallet Transaction
                |--------------------------------------------------------------------------
                */

                if ($admin) {

                    WalletTransaction::create([

                        'user_id' => $admin->id,

                        'transaction_no' => 'PFADM' . now()->format('YmdHis') . rand(100,999),

                        'type' => 'credit',

                        'transaction_type' => 'PAN FIND',

                        'amount' => $amount,

                        'opening_balance' => $adminOpening,

                        'closing_balance' => $admin->wallet_balance,

                        'remark' => 'PAN Find Income',

                        'status' => 'Success',

                    ]);

                }

            });

            return response()->json([

                'success' => true,

                'message' => 'PAN Find request submitted successfully.',

                'redirect' => route(

                    'retailer.pan-find.history'

                ),

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 422);

        }
    }
    
    /**
     * ==========================================
     * History
     * ==========================================
     */
    public function history()
    {
        $histories = PanFindHistory::where(

            'user_id',

            auth()->id()

        )
        ->latest()
        ->paginate(20);

        return view(

            'retailer.pan-find.history',

            compact('histories')

        );
    }
}