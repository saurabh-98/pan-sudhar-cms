<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Models\Offer;

class CheckoutService
{
    protected $cartRepo;

    public function __construct(CartRepository $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    /* =========================
       🔥 MAIN CART CALCULATION
    ========================= */
    public function calculate($userId = null, $couponCode = null, $order = null)
    {
        // 🔥 SOURCE SWITCH
        if ($order) {
            $items = $order->items;
        } else {
            $items = $this->cartRepo->getUserCart($userId);
        }

        if ($items->isEmpty()) {
            return $this->emptyResponse();
        }

        // ✅ Subtotal
        $subtotal = $items->sum(function ($i) use ($order) {
            return $order
                ? $i->price * $i->quantity   // order items
                : $i->menu->price * $i->qty; // cart items
        });

        // ✅ Discount
        $discount = $order
            ? ($order->discount ?? 0)
            : $this->calculateDiscount($subtotal, $couponCode);

        // ✅ Delivery
        $delivery = $order
            ? ($order->delivery_charge ?? 0)
            : ($subtotal > 500 ? 0 : 40);

        // ✅ Taxable
        $taxable = max($subtotal - $discount, 0);

        // ✅ GST
        $cgst = round($taxable * 0.09, 2);
        $sgst = round($taxable * 0.09, 2);
        $tax  = $cgst + $sgst;

        // ✅ FINAL TOTAL (ONLY ADD TAX ONCE)
        $total = max(round($taxable + $tax + $delivery, 2), 0);

        return [
            'items'     => $items,
            'subtotal'  => round($subtotal, 2),
            'taxable'   => round($taxable, 2),

            'cgst'      => $cgst,
            'sgst'      => $sgst,
            'tax'       => $tax,

            'delivery'  => round($delivery, 2),
            'discount'  => round($discount, 2),
            'total'     => $total,
            'coupon'    => $couponCode
        ];
    }
    
        /* =========================
            DISCOUNT LOGIC
        ========================= */
        
        private function calculateDiscount($subtotal, $couponCode = null)
        {
            if (!$couponCode) return 0;

            $couponCode = strtolower(trim($couponCode));

            $offer = Offer::whereRaw('LOWER(code) = ?', [$couponCode])
                ->where('is_active', 1)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
                })
                ->first();

            if (!$offer) return 0;

            // ✅ MIN ORDER CHECK
            if (!empty($offer->min_order) && $subtotal < $offer->min_order) {
                return 0;
            }

            // ✅ SAFE VALUE
            $value = 0;

            if (!is_null($offer->value) && $offer->value != '') {
                $value = (float) $offer->value;
            } elseif (!is_null($offer->discount) && $offer->discount != '') {
                $value = (float) $offer->discount;
            }

            if ($value <= 0) return 0;

            $type = strtolower(trim($offer->type));

            if ($type === 'fixed') {
                $discount = $value;
            } elseif (in_array($type, ['percent','percentage'])) {
                $discount = ($subtotal * $value) / 100;
            } else {
                return 0;
            }

            if (!empty($offer->max_discount)) {
                $discount = min($discount, $offer->max_discount);
            }

            return min(round($discount, 2), $subtotal);
        }

        /* =========================
        EMPTY RESPONSE
        ========================= */
        private function emptyResponse()
        {
            return [
                'items'     => [],
                'subtotal'  => 0,
                'taxable'   => 0,
                'cgst'      => 0,
                'sgst'      => 0,
                'tax'       => 0,
                'delivery'  => 0,
                'discount'  => 0,
                'total'     => 0,
                'coupon'    => null
            ];
        }
}