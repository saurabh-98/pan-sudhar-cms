<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Models\Retailer;
use App\Models\Customer;
use App\Models\WalletTransaction;
use App\Models\PanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | AUTH USER
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | RETAILER DETAILS
        |--------------------------------------------------------------------------
        */

        $retailer = Retailer::where(

            'email',
            $user->email

        )->first();

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        if (!$retailer) {

            abort(404, 'Retailer not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | DYNAMIC RETAILER STATS
        |--------------------------------------------------------------------------
        */

        // PAN Services Count
        $panServices = PanApplication::where(

            'user_id',
            $user->id

        )->count();

        // Aadhaar Services Count
        $aadhaarServices = 0;

        // Total Customers
        $totalCustomers = 0;

        // Wallet Balance
        $walletBalance =
            $user->wallet_balance ?? 0;

        // Total Verifications
        $totalVerifications = 0;

        // Utility Services
        $utilityServices = 0;

        // Total Transactions
        $totalTransactions = WalletTransaction::where(

            'user_id',
            $user->id

        )->count();

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RATE
        |--------------------------------------------------------------------------
        */

        $approvedApplications =
            PanApplication::where(

                'user_id',
                $user->id

            )

            ->where(

                'status',
                'approved'

            )

            ->count();

        $totalApplications =
            PanApplication::where(

                'user_id',
                $user->id

            )

            ->count();

        $successRate =
            $totalApplications > 0

            ? round(
                ($approvedApplications / $totalApplications) * 100
            )

            : 0;

        /*
        |--------------------------------------------------------------------------
        | RECENT SERVICES
        |--------------------------------------------------------------------------
        */

        $recentServices = [

            [

                'service' => 'New PAN Card',
                'customer' => 'Rahul Kumar',
                'status' => 'Completed',
                'date' => now()->subDays(1)
                               ->format('d M Y'),

            ],

            [

                'service' => 'Aadhaar Update',
                'customer' => 'Amit Singh',
                'status' => 'Pending',
                'date' => now()->subDays(2)
                               ->format('d M Y'),

            ],

            [

                'service' => 'PAN Correction',
                'customer' => 'Pooja Sharma',
                'status' => 'Approved',
                'date' => now()->subDays(3)
                               ->format('d M Y'),

            ],

            [

                'service' => 'GST Verification',
                'customer' => 'Ankit Verma',
                'status' => 'Completed',
                'date' => now()->subDays(4)
                               ->format('d M Y'),

            ],

        ];

        /*
        |--------------------------------------------------------------------------
        | QUICK SERVICES
        |--------------------------------------------------------------------------
        */

        $quickServices = [

            [

                'title' => 'Apply New PAN',
                'icon'  => 'fa-id-card',
                'url'   => route('retailer.pan.apply'),

            ],

            [

                'title' => 'PAN Correction',
                'icon'  => 'fa-pen',
                'url'   => route('retailer.pan.correction'),

            ],

            [

                'title' => 'PAN Verification',
                'icon'  => 'fa-check-circle',
                'url'   => route('retailer.pan.verify'),

            ],

            [

                'title' => 'Company PAN',
                'icon'  => 'fa-building',
                'url'   => route('retailer.pan.company'),

            ],

            [

                'title' => 'PAN Training',
                'icon'  => 'fa-headset',
                'url'   => route('retailer.pan.training'),

            ],

            [

                'title' => 'PAN Find',
                'icon'  => 'fa-search',
                'url'   => route('retailer.pan.find'),

            ],

            [

                'title' => 'File ITR',
                'icon'  => 'fa-file-invoice-dollar',
                'url'   => route('retailer.itr.file'),

            ],

            [

                'title' => 'ITR History',
                'icon'  => 'fa-history',
                'url'   => route('retailer.itr.history'),

            ],

            [

                'title' => 'ITR Correction',
                'icon'  => 'fa-edit',
                'url'   => route('retailer.itr.correction'),

            ],

            [

                'title' => 'Form 16',
                'icon'  => 'fa-file-alt',
                'url'   => route('retailer.itr.form16'),

            ],

            [

                'title' => 'GST Return',
                'icon'  => 'fa-receipt',
                'url'   => route('retailer.itr.gst.return'),

            ],

            [

                'title' => 'Bank Verification',
                'icon'  => 'fa-university',
                'url'   => route('retailer.verification.bank'),

            ],

            [

                'title' => 'Voter Verification',
                'icon'  => 'fa-vote-yea',
                'url'   => route('retailer.verification.voter'),

            ],

            [

                'title' => 'RC Verification',
                'icon'  => 'fa-car',
                'url'   => route('retailer.verification.rc'),

            ],

            [

                'title' => 'DL Verification',
                'icon'  => 'fa-id-badge',
                'url'   => route('retailer.verification.dl'),

            ],

            [

                'title' => 'GST Verification',
                'icon'  => 'fa-file-invoice',
                'url'   => route('retailer.verification.gst'),

            ],

            [

                'title' => 'Passport Verification',
                'icon'  => 'fa-passport',
                'url'   => route('retailer.verification.passport'),

            ],

            [

                'title' => 'Aadhaar PVC',
                'icon'  => 'fa-address-card',
                'url'   => route('retailer.tools.aadhaar.pvc'),

            ],

            [

                'title' => 'Hisab Kitab',
                'icon'  => 'fa-book',
                'url'   => route('retailer.tools.hisab.kitab'),

            ],

            [

                'title' => 'File Converter',
                'icon'  => 'fa-file',
                'url'   => route('retailer.tools.file.converter'),

            ],

            [

                'title' => 'Passport Photo',
                'icon'  => 'fa-camera',
                'url'   => route('retailer.tools.passport.photo'),

            ],

        ];

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        $notifications = [

            [

                'title' => 'Wallet Recharge',
                'message' => '₹500 added successfully.',

            ],

            [

                'title' => 'PAN Approved',
                'message' => 'Rahul Kumar PAN application approved.',

            ],

            [

                'title' => 'New Update',
                'message' => 'DOB document rules updated.',

            ],

        ];

        $notificationCount =
            count($notifications);

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(

            'retailer.dashboard',

            compact(

                'user',
                'retailer',

                'panServices',
                'aadhaarServices',
                'totalCustomers',
                'walletBalance',
                'totalVerifications',
                'utilityServices',
                'totalTransactions',
                'successRate',

                'recentServices',
                'quickServices',

                'notifications',
                'notificationCount'

            )

        );
    }
}