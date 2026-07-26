<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfitPartnerTransaction extends Model
{
    protected $fillable = [

        'partner_id',

        'service_type',

        'service_id',

        'reference_no',

        'service_amount',

        'executive_commission',

        'distributor_commission',

        'net_profit',

        'profit_percentage',

        'profit_amount',

        'remarks',

    ];

    protected $casts = [

        'service_amount' => 'decimal:2',

        'executive_commission' => 'decimal:2',

        'distributor_commission' => 'decimal:2',

        'net_profit' => 'decimal:2',

        'profit_percentage' => 'decimal:2',

        'profit_amount' => 'decimal:2',

    ];

    /**
     * Partner
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(
            ProfitPartner::class,
            'partner_id'
        );
    }
}