<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['menu_id' => 1, 'price' => 299, 'quantity' => 2],
            ['menu_id' => 2, 'price' => 149, 'quantity' => 1],
        ];

        $total = collect($items)->sum(fn($i) => $i['price'] * $i['quantity']);
        $discount = 50;
        $finalTotal = $total - $discount;

        $order = Order::create([
            'user_id' => 1,
            'total' => $total, // 🔥 FIXED
            'discount' => $discount,
            'final_total' => $finalTotal,
            'offer_code' => 'SAVE50',
            'status' => 'completed',
            'payment_method' => 'COD',
            'payment_status' => 'paid',
            'address' => 'Delhi, India',
            'invoice_no' => 'INV-' . date('Y') . '-' . str_pad(1, 4, '0', STR_PAD_LEFT)
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }
    }
}