<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CheckoutService;
use App\Services\OrderService;

class CheckoutController extends Controller
{
    protected $checkoutService, $orderService;

    public function __construct(CheckoutService $checkoutService, OrderService $orderService)
    {

        $this->checkoutService = $checkoutService;
        $this->orderService = $orderService;
    }

    /* =========================
       CHECKOUT PAGE
    ========================= */
    public function index()
    {
        return view('checkout.index');
    }

    /* =========================
       CALCULATE (MAIN API 🔥)
    ========================= */
    public function calculate(Request $request)
    {
        $request->validate([
            'coupon' => 'nullable|string|max:50'
        ]);

    

        try {

            $data = $this->checkoutService->calculate(
                auth()->id(),
                $request->coupon
            );

            return response()->json([
                'success'   => true,

                // 🔥 IMPORTANT (frontend needs this)
                'items'     => $data['items'] ?? [],

                'subtotal'  => $data['subtotal'] ?? 0,
                'tax'       => $data['tax'] ?? 0,
                'delivery'  => $data['delivery'] ?? 0,
                'discount'  => $data['discount'] ?? 0,
                'total'     => $data['total'] ?? 0,

                // optional
                'coupon'    => $request->coupon
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /* =========================
       PLACE ORDER 🔥
    ========================= */
    public function placeOrder(Request $request)
{
    $request->validate([
        'coupon' => 'nullable|string|max:50'
    ]);

    try {

        $data = $this->checkoutService->calculate(
            auth()->id(),
            $request->coupon
        );

        if (empty($data['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        $order = $this->orderService->placeOrder(
            auth()->id(),
            $data
        );

        // 🔥 ROLE BASED REDIRECT
        $redirect = $this->redirectByRole(auth()->user()->role ?? 'customer');

       return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'order_id' => $order->id,
            'redirect' => $this->redirectByRole(auth()->user()->role)
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}