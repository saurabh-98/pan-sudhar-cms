<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChargeLocation extends Model
{
    protected $fillable = [
        'charge_id',
        'state_id',
        'district_id',
        'price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}