<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialLedger extends Model
{
    protected $fillable = [

        'service_type',

        'service_id',

        'reference_no',

        'retailer_id',

        'executive_id',

        'distributor_id',

        'service_amount',

        'executive_commission',

        'distributor_commission',

        'net_profit',

        'partner_profit',

        'company_profit',

        'charge_code',

        'remarks',

    ];

    protected $casts = [

        'service_amount' => 'decimal:2',

        'executive_commission' => 'decimal:2',

        'distributor_commission' => 'decimal:2',

        'net_profit' => 'decimal:2',

        'partner_profit' => 'decimal:2',

        'company_profit' => 'decimal:2',

    ];

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }

    public function executive()
    {
        return $this->belongsTo(User::class, 'executive_id');
    }

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }
}