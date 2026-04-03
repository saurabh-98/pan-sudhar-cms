<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\DB;
use App\Models\UpiSetting;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use App\Services\CheckoutService;

class OrderService
{
    protected $orderRepository;
    protected $cartRepository;
    protected $checkoutService;

    public function __construct(
        OrderRepository $orderRepository,
        CartRepository $cartRepository,
        CheckoutService $checkoutService
    ) {
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->checkoutService = $checkoutService;
    }

    /* =========================
       PLACE ORDER
    ========================= */
    public function placeOrderFromCart($userId, $coupon = null, $checkoutData = [])
    {
        return DB::transaction(function () use ($userId, $coupon, $checkoutData) {

            $cartItems = $this->cartRepository->getUserCart($userId);

            if ($cartItems->isEmpty()) {
                throw new \Exception("Cart is empty");
            }

            $orderType = $checkoutData['order_type'] ?? 'outside';

            if (empty($checkoutData['mobile'])) {
                throw new \Exception("Mobile number is required");
            }

            if ($orderType === 'inside' && empty($checkoutData['table_number'])) {
                throw new \Exception("Table number is required");
            }

            if ($orderType === 'outside' && empty($checkoutData['address'])) {
                throw new \Exception("Address is required");
            }

            // ✅ USE CHECKOUT SERVICE (SINGLE SOURCE)
            $checkout = $this->checkoutService->calculate($userId, $coupon);

            $order = $this->orderRepository->createOrder([
                'user_id'     => $userId,
                'mobile'      => $checkoutData['mobile'],
                'order_type'  => $orderType,
                'table_number'=> $checkoutData['table_number'] ?? null,
                'address'     => $checkoutData['address'] ?? null,

                // ✅ STORE EVERYTHING
                'total'       => $checkout['subtotal'],
                'discount'    => $checkout['discount'],
                'tax'         => $checkout['tax'],        // 🔥 NEW
                'delivery'    => $checkout['delivery'],   // 🔥 NEW
                'final_total' => $checkout['total'],

                'status'      => 'pending',
            ]);

            $this->orderRepository->addItems($order->id, $cartItems);

            $order->update([
                'invoice_no' => $this->generateInvoiceNumber($order->id)
            ]);

            $this->cartRepository->clearByUser($userId);

            return $order;
        });
    }

    /* =========================
       FIND / LIST
    ========================= */
    public function getOrderWithDetails($id)
    {
        return $this->orderRepository->getOrderWithDetails($id);
    }

    public function findWithItems($id)
    {
        return $this->orderRepository->getOrderWithDetails($id);
    }

    public function find($id)
    {
        return $this->orderRepository->getOrderWithDetails($id);
    }

    public function list($request = null)
    {
        $query = $this->orderRepository->queryWithRelations();

        if ($request && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request && $request->status) {
            $query->where('status', $request->status);
        }

        // ✅ DESC ORDER (LATEST FIRST)
        return $query->orderBy('id', 'desc')->get()->map(function ($order) {

            // ✅ CALCULATE CORRECT VALUES
           $summary = $this->checkoutService->calculate(null, null, $order);

            return [
                'id' => $order->id,
                'user' => [
                    'name' => $order->user->name ?? 'N/A'
                ],

                'total' => number_format($summary['subtotal'], 2),
                'discount' => number_format($summary['discount'], 2),
                'final_total' => number_format($summary['total'], 2),
                'date' => $order->created_at->format('d M Y, h:i A'),
                'status' => $order->status,
                'payment_status' => $order->payment_status,
            ];
        });
    }


    public function listForTracking()
    {
        return $this->orderRepository
            ->queryWithRelations()
            ->orderBy('id','desc')
            ->get(); // ✅ RETURN OBJECTS
    }

    public function listForInvoice()
    {
        return $this->orderRepository
            ->queryWithRelations()
            ->orderBy('id','desc')
            ->get();
    }

    /* =========================
       STATUS / PAYMENT
    ========================= */
    public function updateStatus($id, $status)
    {
        $valid = ['pending', 'preparing', 'ready', 'delivered'];

        if (!in_array($status, $valid)) {
            throw new \Exception('Invalid status');
        }

        return $this->orderRepository->updateStatus($id, $status);
    }

    public function updatePaymentStatus($id, $status)
    {
        $valid = ['pending', 'paid', 'failed'];

        if (!in_array($status, $valid)) {
            throw new \Exception('Invalid payment status');
        }

        return $this->orderRepository->updatePaymentStatus($id, $status);
    }

    public function getUserOrders()
    {
        return $this->orderRepository->getUserOrders(Auth::id());
    }

    public function getCustomerOrders($userId, $status = null)
    {
        return $this->orderRepository->getUserOrders($userId, $status);
    }

    /* =========================
       QR
    ========================= */
    public function generateQR($order, $finalTotal)
    {
        $amount = number_format((float)$finalTotal, 2, '.', '');

        $upi = UpiSetting::where('is_active', 1)->first();

        if (!$upi) return null; // safer

        $upiLink = "upi://pay?pa={$upi->upi_id}&pn={$upi->name}&am={$amount}&cu=INR";

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($upiLink)
            ->size(250)
            ->margin(10)
            ->build();

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }


    /* =========================
       INVOICE
    ========================= */
    public function getInvoiceData($id)
    {
        $order = $this->find($id);
        $qr = $this->generateQR($order);

        return [
            'order' => $order,
            'qr'    => $qr,

            // ✅ USE STORED VALUES (NO RE-CALCULATION)
            'subtotal' => $order->total,
            'discount' => $order->discount,
            'tax'      => $order->tax,
            'delivery' => $order->delivery,

            // ✅ SPLIT GST
            'cgst' => round($order->tax / 2, 2),
            'sgst' => round($order->tax / 2, 2),
        ];
    }

    public function generateInvoice($id)
    {
        $data = $this->getInvoiceData($id);

        return Pdf::loadView('admin.invoices.enterprise', $data)
            ->setPaper('A4', 'portrait');
    }

    /* =========================
       INVOICE NUMBER
    ========================= */
    private function generateInvoiceNumber($id)
    {
        return 'INV-' . date('Y') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);
    }
}