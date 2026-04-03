<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository
{
    /* =========================
       FIND CART ITEM
    ========================= */
    public function findByUserAndMenu($userId, $menuId)
    {
        return Cart::where('user_id', $userId)
            ->where('menu_id', $menuId)
            ->first();
    }

    /* =========================
       FIND BY ID
    ========================= */
    public function findById($id)
    {
        return Cart::with('menu')->findOrFail($id);
    }

    /* =========================
       CREATE
    ========================= */
    public function create(array $data)
    {
        return Cart::create($data);
    }

    /* =========================
       UPDATE QTY
    ========================= */
    public function updateQty($cart, $qty)
    {
        $cart->update([
            'qty' => $qty
        ]);

        return $cart;
    }

    /* =========================
       GET USER CART
    ========================= */
    public function getUserCart($userId)
    {
        return Cart::with('menu')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->get();
    }

    public function count($userId)
    {
        return Cart::where('user_id', $userId)
            ->sum('qty'); 
    }

    /* =========================
       GET SINGLE USER ITEM
    ========================= */
    public function getUserItem($userId, $id)
    {
        return Cart::where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();
    }

    /* =========================
       COUNT ITEMS
    ========================= */
    
     public function updateQtyById($id, $qty)
    {
        return Cart::where('id', $id)->update(['qty' => $qty]);
    }

    /* =========================
       DELETE ITEM
    ========================= */
    public function delete($id)
    {
        return Cart::where('id', $id)->delete();
    }

    /* =========================
       CLEAR CART
    ========================= */
    public function clearByUser($userId)
    {
        return Cart::where('user_id', $userId)->delete();
    }


    

    /* =========================
       CHECK EXISTS
    ========================= */
    public function exists($userId, $menuId)
    {
        return Cart::where('user_id', $userId)
            ->where('menu_id', $menuId)
            ->exists();
    }
}