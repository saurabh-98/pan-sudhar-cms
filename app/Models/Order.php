<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'mobile',
        'order_type',
        'table_number',
        'total',
        'discount',
        'final_total',
        'offer_code',
        'status',
        'payment_method',
        'payment_status',
        'address',
        'invoice_no'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

  
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

  
    public function isCompleted()
    {
        return $this->status === 'completed';
    }


    public function isPending()
    {
        return $this->status === 'pending';
    }

  
    public function isDineIn()
    {
        return $this->order_type === 'inside';
    }

    public function isDelivery()
    {
        return $this->order_type === 'outside';
    }
}