<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\CheckoutService; // ✅ ADD
use App\DTO\OrderDTO;
use Illuminate\Http\Request;

class staffOrderController extends Controller
{
    protected $orderService;
    protected $checkoutService;

     public function __construct(OrderService $orderService, CheckoutService $checkoutService)
    {
        $this->orderService = $orderService;
        $this->checkoutService = $checkoutService; // ✅ ADD
     }  
   
    /* ================= ORDERS ================= */

    public function index()
    {
       
        return view('staff.orders.index');
    }

    public function list()
    {
        return response()->json([
            'data' => $this->orderService->list()
        ]);
    }


    public function show($id)
    {
        try {

            $order = $this->orderService->findWithItems($id);
          
            return response()->json([
            'id' => $order->id,
            'invoice_no' => $order->invoice_no,
            'created_at' => $order->created_at,
            'status' => $order->status,
            'mobile' => $order->mobile,
            'order_type' => $order->order_type,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'address' => $order->address,
            'discount' => $order->discount,
            'final_total' => $order->final_total,

            'user' => [
                'name' => $order->user->name ?? 'Guest'
            ],

            'items' => $order->items->map(function ($item) {
                return [
                    'menu' => [
                        'name' => $item->menu->name ?? 'Item',
                        
                    ],
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ];
            })
        ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'       => 'required|array|min:1',
            'mobile'      => 'required|digits_between:10,15',
            'order_type'  => 'required|in:inside,outside'
        ]);

        if ($request->order_type === 'inside' && !$request->table_number) {
            return response()->json([
                'success' => false,
                'message' => 'Table number required'
            ]);
        }

        if ($request->order_type === 'outside' && !$request->address) {
            return response()->json([
                'success' => false,
                'message' => 'Address required'
            ]);
        }

        $dto = new OrderDTO(
            user_id: auth()->id(),
            items: $request->items,
            mobile: $request->mobile,
            order_type: $request->order_type,
            table_number: $request->order_type === 'inside' ? $request->table_number : null,
            address: $request->order_type === 'outside' ? $request->address : null
        );

        $order = $this->orderService->store($dto);

        return response()->json([
            'success'  => true,
            'message'  => 'Order created successfully',
            'order_id' => $order->id
        ]);
    }

    /* ================= STATUS ================= */

    public function updateStatus(Request $request, $id)
    {
        // ✅ MATCH FRONTEND STATUS
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,delivered'
        ]);

        $order = $this->orderService->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status'  => $order->status
        ]);
    }


        public function updatePayment(Request $request, $id)
        {
            $validated = $request->validate([
                'payment_status' => 'required|in:pending,paid,failed'
            ]);

            $order = $this->orderService->updatePaymentStatus($id, $validated['payment_status']);

            return response()->json([
                'success' => true,
                'payment_status' => $order->payment_status
            ]);
        }

    /* ================= INVOICE VIEW ================= */

    
    /* ================= TRACKING ================= */

    public function tracking()
    {
        $orders = $this->orderService->list();
        return view('staff.orders.tracking', compact('orders'));
    }

    /* ================= INVOICE LIST ================= */

     public function invoice()
    {
      
        $orders = $this->orderService->list();
        return view('staff.invoices.index', compact('orders'));
    }

    public function invoiceList()
    {
        $orders = $this->orderService->listForInvoice();

        return response()->json([
            'data' => $orders->map(function ($order) {

                return [
                    'id' => $order->id,
                    'invoice_no' => $order->invoice_no ?? 'N/A',
                    'customer' => $order->user->name ?? 'N/A',
                    'total' => number_format($order->final_total, 2),
                    'payment_status' => ucfirst($order->payment_status ?? 'pending'),
                    'status' => $order->status,
                    'actions' => '
                        <a href="'.route('staff.orders.invoice',$order->id).'" target="_blank" class="btn btn-sm btn-primary">👁</a>
                        <a href="'.route('staff.orders.invoice.download',$order->id).'" class="btn btn-sm btn-success">⬇</a>
                    '
                ];
            })
        ]);
    }

    public function invoices($id)
    {
        
        try {

            $order = $this->orderService->findWithItems($id);
            
            $summary = $this->checkoutService->calculate(null, null, $order);
           
            //  GENERATE QR HERE
            $qr = $this->orderService->generateQR($order,$summary['total']);
            

            return view('staff.invoices.view', [
                'order'      => $order,
                'subtotal'   => $summary['subtotal'],
                'discount'   => $summary['discount'],
                'delivery'   => $summary['delivery'],
                'cgst'       => $summary['cgst'],
                'sgst'       => $summary['sgst'],
                'finalTotal' => $summary['total'],
                'qr'         => $qr // ✅ IMPORTANT
            ]);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /* ================= ✅ PDF DOWNLOAD FIXED ================= */

    public function downloadInvoice($id)
    {
        
        try {

            $order = $this->orderService->findWithItems($id);

            if (!$order) {
                return back()->with('error', 'Order not found');
            }

            $summary = $this->checkoutService->calculate(null, null, $order);
            $qr = $this->orderService->generateQR($order,$summary['total']);


            $pdf = app('dompdf.wrapper')->loadView('staff.invoices.view', [
                'order'      => $order,
                'subtotal'   => $summary['subtotal'],
                'discount'   => $summary['discount'],
                'delivery'   => $summary['delivery'],
                'cgst'       => $summary['cgst'],
                'sgst'       => $summary['sgst'],
                'finalTotal' => $summary['total'],
                'qr'         => $qr,
                
            ]);

            return $pdf->download('invoice_'.$id.'.pdf');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

}