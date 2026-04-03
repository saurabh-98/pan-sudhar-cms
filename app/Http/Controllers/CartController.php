<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use App\Services\OfferService;
use App\DTO\CartDTO;
use App\Http\Requests\CartRequest;
use Illuminate\Http\Request;
use App\Models\Offer;

class CartController extends Controller
{
    protected $cartService;
    protected $orderService;
    protected $offerService;

    public function __construct(
        CartService $cartService,
        OrderService $orderService,
        OfferService $offerService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->offerService = $offerService;
    }

    /* =========================
       CART PAGE
    ========================= */
    public function view()
    {
        return view('cart.index');
    }

    /* =========================
       ADD TO CART
    ========================= */
    public function add(CartRequest $request)
    {
        $dto = new CartDTO(
            auth()->id(),
            $request->menu_id,
            $request->qty ?? 1
        );

        $this->cartService->addToCart($dto);

        return response()->json([
            'success' => true,
            'message' => 'Added to cart'
        ]);
    }

    /* =========================
       GET CART
    ========================= */
    public function index()
    {
        if (!auth()->check()) {
            return response()->json([]);
        }

        return response()->json(
            $this->cartService->getCart(auth()->id())
        );
    }

    /* =========================
       UPDATE QUANTITY
    ========================= */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:carts,id',
            'qty' => 'required|integer|min:1'
        ]);

        $this->cartService->updateQuantity(
            $request->id,
            $request->qty,
            auth()->id()
        );

        return response()->json(['success' => true]);
    }

    /* =========================
       REMOVE ITEM
    ========================= */
    public function remove($id)
    {
        $this->cartService->removeItem($id);

        return response()->json([
            'success' => true,
            'message' => 'Removed'
        ]);
    }

    /* =========================
       CHECKOUT PAGE
    ========================= */
    public function checkout()
    {
        $items = $this->cartService->getCartItems(auth()->id());

        return view('checkout.index', compact('items'));
    }

    /* =========================
       APPLY COUPON (FIXED)
    ========================= */
    public function applyOffer(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $offer = Offer::where('code', $request->code)->first();

        if (!$offer || !$offer->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon'
            ]);
        }

        $cartItems = $this->cartService->getCartItems(auth()->id());

        $subtotal = $cartItems->sum(fn($i) => $i->menu->price * $i->qty);

        $result = $this->offerService->applyOffer($offer, $subtotal);

        return response()->json([
            'success'      => true,
            'offer_id'     => $offer->id,
            'discount'     => $result['discount'],
            'final_total'  => $result['final_total'],
            'coupon'       => $offer->code
        ]);
    }

    /* =========================
       CART COUNT
    ========================= */
    public function count()
    {
        if (!auth()->check()) {
            return response()->json(['count' => 0]);
        }

        return response()->json([
            'count' => $this->cartService->getCartCount(auth()->id())
        ]);
    }

  
    public function placeOrder(Request $request)
    {
        try {

            // ✅ BASE VALIDATION
            $request->validate([
                'mobile'      => 'required',
                'order_type'  => 'required|in:inside,outside',
                'coupon'      => 'nullable|string'
            ]);

            $orderType = $request->order_type;

            // 🔥 CONDITIONAL VALIDATION (CLEAN)
            if ($orderType === 'inside' && empty($request->table_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Table number is required for dine-in'
                ], 422);
            }

            if ($orderType === 'outside' && empty($request->address)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address is required for delivery'
                ], 422);
            }

            // ✅ SERVICE CALL
            $order = $this->orderService->placeOrderFromCart(
                auth()->id(),
                $request->coupon,
                [
                    'mobile'        => $request->mobile,
                    'order_type'    => $orderType,
                    'table_number'  => $orderType === 'inside' ? $request->table_number : null,
                    'address'       => $orderType === 'outside' ? $request->address : null,
                ]
            );

            // ✅ RETURN PROPER REDIRECT (IMPORTANT FIX)
            return response()->json([
                'success'  => true,
                'message'  => 'Order placed successfully',

                // 🔥 BEST: redirect to order details page
                'redirect' => route('customer.orders', $order->id),

                // BONUS: send order id
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}