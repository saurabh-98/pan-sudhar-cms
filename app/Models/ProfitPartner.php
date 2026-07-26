<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfitPartner extends Model
{
    protected $fillable = [
        'partner_name',
        'profit_percentage',
        'status',
    ];

    protected $casts = [
        'profit_percentage' => 'decimal:2',
        'status' => 'boolean',
    ];

    /**
     * Profit Transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(
            ProfitPartnerTransaction::class,
            'partner_id'
        );
    }

    /**
     * Active Partners Scope
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}