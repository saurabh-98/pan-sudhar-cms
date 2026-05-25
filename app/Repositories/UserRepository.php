<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Order;

class UserRepository
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */

    public function getAll()
    {
        return User::with('roles')
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */

    public function create(array $data)
    {
        return User::create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | USERS WITH ORDERS
    |--------------------------------------------------------------------------
    */

    public function getAllUsersWithOrders()
    {
        return User::with([
                'orders',
                'roles'
            ])
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND USER
    |--------------------------------------------------------------------------
    */

    public function findById($id)
    {
        return User::with('roles')
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY EMAIL
    |--------------------------------------------------------------------------
    */

    public function findByEmail($email)
    {
        return User::with('roles')
            ->where('email', $email)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function update($user, array $data)
    {
        $user->update($data);

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        return User::destroy($id);
    }

    /*
    |--------------------------------------------------------------------------
    | GET ONLY CUSTOMERS
    |--------------------------------------------------------------------------
    */

    public function getCustomersWithOrders()
    {
        return User::role('Customer')
            ->with([
                'orders',
                'roles'
            ])
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | GET USERS BY ROLE
    |--------------------------------------------------------------------------
    */

    public function getUsersByRole($role)
    {
        return User::role($role)
            ->with('roles')
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER ORDERS
    |--------------------------------------------------------------------------
    */

    public function getOrdersByUser($userId)
    {
        return Order::where(
                'user_id',
                $userId
            )
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER STATS
    |--------------------------------------------------------------------------
    */

    public function getCustomerStats($userId)
    {
        $orders = Order::where(
            'user_id',
            $userId
        );

        return [

            'totalOrders' => $orders->count(),

            'totalSpent' => $orders
                ->sum('final_total')
        ];
    }
}