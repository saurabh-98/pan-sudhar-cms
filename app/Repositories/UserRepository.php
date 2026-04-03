<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Order;

class UserRepository
{
    /* =========================
       GET ALL USERS
    ========================= */
    public function getAll()
    {
        return User::latest()->get();
    }

    /* =========================
       CREATE USER
    ========================= */
    public function create(array $data)
    {
        return User::create($data);
    }

    /* =========================
       USERS WITH ORDERS
    ========================= */
    public function getAllUsersWithOrders()
    {
        return User::with('orders')->latest()->get();
    }

    /* =========================
       FIND USER
    ========================= */
    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /* =========================
       UPDATE USER
    ========================= */
    public function update($user, array $data)
    {
        $user->update($data);
        return $user;
    }

    /* =========================
       DELETE USER
    ========================= */
    public function delete($id)
    {
        return User::destroy($id);
    }

    /* =========================
       🔥 CUSTOMER: GET ONLY CUSTOMERS
    ========================= */
    public function getCustomersWithOrders()
    {
        return User::where('role', 'customer')
            ->with('orders')
            ->latest()
            ->get();
    }

    /* =========================
       🔥 CUSTOMER ORDERS
    ========================= */
    public function getOrdersByUser($userId)
    {
        return Order::where('user_id', $userId)
            ->latest()
            ->get();
    }

    /* =========================
       🔥 CUSTOMER STATS (OPTIONAL)
    ========================= */
    public function getCustomerStats($userId)
    {
        $orders = Order::where('user_id', $userId);

        return [
            'totalOrders' => $orders->count(),
            'totalSpent'  => $orders->sum('final_total')
        ];
    }
}