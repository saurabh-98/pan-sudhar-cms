<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\ReservationService;
use App\Services\CheckoutService;
use App\Models\Order;

class CustomerController extends Controller
{
    protected $orderService, $reservationService, $checkoutService;

    public function __construct(
        OrderService $orderService,
        CheckoutService $checkoutService,
        ReservationService $reservationService
    ) {
        $this->orderService = $orderService;
        $this->checkoutService = $checkoutService;
        $this->reservationService = $reservationService;
    }

    /* =========================
       DASHBOARD
    ========================= */
    public function dashboard()
    {
        $userId = auth()->id();

        $orders = $this->orderService->getCustomerOrders($userId);
        $reservations = $this->reservationService->getCustomerReservations($userId);

        return view('customer.dashboard', [
            'ordersCount' => $orders->count(),
            'reservationCount' => $reservations->count(),
            'totalSpent' => $orders->sum('total_amount'),
            'recentOrders' => $orders->take(3)
        ]);
    }

    /* =========================
       ORDERS
    ========================= */
    public function orders()
    {
        return view('customer.orders');
    }

    public function orderList(Request $request)
    {
        // ✅ CLEAN STATUS (IMPORTANT FIX)
        $status = $request->filled('status') ? $request->status : null;

        // ✅ GET ORDERS (PASS NULL IF EMPTY)
        $orders = $this->orderService->getCustomerOrders(
            auth()->id(),
            $status
        );

        $data = $orders->map(function ($order) {

            // ✅ SINGLE CALCULATION SOURCE
            $summary = $this->checkoutService->calculate(null, null, $order);

            return [
                'id' => $order->id,
                'mobile' => $order->mobile,
                'order_type' => ucfirst($order->order_type),

                // ✅ FIXED AMOUNT FORMAT (NO DOUBLE ₹)
                'final_total' => $order->status === 'cancelled'
                    ? '0.00'
                    : number_format($summary['total'], 2),

                'status' => $order->status,
                'status_label' => ucfirst($order->status),
                'payment_status' => $order->payment_status,
                'payment_status_label' => ucfirst($order->payment_status),

                // ✅ RETURN RAW DATE (FOR JS FORMAT)
                'created_at' => $order->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    /* =========================
       ORDER DETAILS
    ========================= */
    public function orderDetails($id)
    {
        $order = $this->orderService->getOrderWithDetails($id);

        if (!$order) {
            abort(404, 'Order not found');
        }

        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // ✅ CORRECT USAGE
        $summary = $this->checkoutService->calculate(null, null, $order);

        return view('customer.order-details', [
            'order' => $order,
            'summary' => $summary
        ]);
    }

    /* =========================
       CANCEL ORDER
    ========================= */
    public function cancelOrder($id)
    {
        try {
            $order = Order::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if (in_array($order->status, ['delivered'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled'
                ]);
            }

            if ($order->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already cancelled'
                ]);
            }

            $order->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /* =========================
       RESERVATIONS
    ========================= */
    public function reservations()
    {
        return view('customer.reservations');
    }

    public function reservationList()
    {
        return response()->json([
            'data' => $this->reservationService->getCustomerReservations(auth()->id())
        ]);
    }

    public function cancelReservation($id)
    {
        $this->reservationService->updateStatus($id, 'cancelled');

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation cancelled successfully'
        ]);
    }

    /* =========================
       DOWNLOAD INVOICE
    ========================= */
    public function downloadInvoice($id)
    {
        $order = $this->orderService->getOrderWithDetails($id);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return $this->orderService
            ->generateInvoice($id)
            ->stream('invoice-' . $order->id . '.pdf');
    }

    /* =========================
       PROFILE
    ========================= */
    public function profile()
    {
        return view('customer.profile');
    }
}