<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $fillable = [
        'retailer_id',
        'amount',
        'upi_id',
        'merchant_name',
        'utr',
        'screenshot',
        'remarks',
        'status',
        'verified_by',
        'verified_at',
        'admin_remarks'
    ];

    public function retailer()
    {
        return $this->belongsTo(User::class,'retailer_id');
    }
}
