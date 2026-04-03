<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UpiSetting;

class OrderRepository
{
    /* =========================
       CREATE ORDER
    ========================= */
    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    /* =========================
       ADD ITEMS (BULK INSERT)
    ========================= */
    public function addItems($orderId, $items)
    {
        $insertData = [];

        foreach ($items as $item) {

            if (!$item->menu) {
                throw new \Exception("Menu not found for item ID: " . $item->id);
            }

            $insertData[] = [
                'order_id'   => $orderId,
                'menu_id'    => $item->menu_id,
                'quantity'   => $item->qty,
                'price'      => $item->menu->price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        OrderItem::insert($insertData);
    }

    /* =========================
       BASE QUERY (RELATIONS)
    ========================= */
    private function baseQuery()
    {
        return Order::with(['items.menu', 'user']);
    }

    /* =========================
       ✅ NEW: QUERY WITH RELATIONS (IMPORTANT)
    ========================= */
    public function queryWithRelations()
    {
        return $this->baseQuery();
    }

    /* =========================
       GET ALL
    ========================= */
    public function all()
    {
        return $this->baseQuery()->latest()->get();
    }

    /* =========================
       PAGINATION
    ========================= */
    public function paginate($perPage = 10)
    {
        return $this->baseQuery()->latest()->paginate($perPage);
    }

    /* =========================
       FILTER
    ========================= */
    public function filter(array $filters)
    {
        $query = $this->baseQuery();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->get();
    }

    /* =========================
       SEARCH
    ========================= */
    public function search($keyword)
    {
        return $this->baseQuery()
            ->where(function ($q) use ($keyword) {
                $q->where('id', $keyword)
                  ->orWhereHas('user', function ($q2) use ($keyword) {
                      $q2->where('name', 'like', "%$keyword%");
                  });
            })
            ->latest()
            ->get();
    }

    /* =========================
       UPDATE STATUS
    ========================= */
    public function updateStatus($id, $status)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'status' => $status
        ]);

        return $order;
    }

    /* =========================
       USER ORDERS
    ========================= */
    public function getUserOrders($userId, $status = null)
    {
        $query = $this->baseQuery()
            ->where('user_id', $userId);

        // ✅ APPLY FILTER ONLY IF STATUS EXISTS
        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }
    /* =========================
       FIND BY ID
    ========================= */
    public function findByOrderId($orderId)
    {
        return $this->baseQuery()
            ->where('id', $orderId)
            ->first();
    }

    /* =========================
       ORDER DETAILS (MODAL)
    ========================= */
    public function getOrderWithDetails($id)
    {
        return $this->baseQuery()->findOrFail($id);
    }

    /* =========================
       DELETE ORDER
    ========================= */
    public function delete($id)
    {
        $order = Order::findOrFail($id);

        $order->items()->delete();
        $order->delete();

        return true;
    }

        /* =========================
    UPDATE PAYMENT STATUS
    ========================= */
    public function updatePaymentStatus($id, $status)
    {
        $order = Order::findOrFail($id); // ✅ get model

        $order->update([
            'payment_status' => $status
        ]);

        return $order; // ✅ return object
    }
    
    /* =========================
       UPI SET ACTIVE
    ========================= */
    public function setActive($id)
    {
        UpiSetting::query()->update(['is_active' => 0]);

        UpiSetting::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }
}