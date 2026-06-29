<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeCommission extends Model
{
    protected $fillable = [
        'charge_id',
        'role',
        'type',
        'value',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'decimal:2',
    ];

    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }
}