<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price'
    ];

    protected $appends = [
        'subtotal',
        'formatted_price',
        'formatted_subtotal'
    ];

   

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

   
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    public function getFormattedSubtotalAttribute()
    {
        return '₹' . number_format($this->subtotal, 2);
    }

    public function getRawSubtotal()
    {
        return $this->price * $this->quantity;
    }

    public function getGSTAmount($rate = 18)
    {
        return ($this->getRawSubtotal() * $rate) / 100;
    }
}