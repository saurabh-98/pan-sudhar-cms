<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    

    public function staffDashboard()
    {
        return view('staff.dashboard', [
            'orders' => Order::count(),
            'pending' => Order::where('status','pending')->count(),
            'delivered' => Order::where('status','delivered')->count(),
            'recentOrders' => Order::latest()->take(10)->get()
        ]);
    }

   
}