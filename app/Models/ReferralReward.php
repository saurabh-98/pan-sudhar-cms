<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralReward extends Model
{
    protected $fillable = [

        'referrer_id',

        'referred_id',

        'reward',

        'status',

        'remarks',

        'approved_at',

        'release_at',

        'wallet_credited'

    ];

    protected $casts = [

        'approved_at' => 'datetime',

        'release_at' => 'datetime',

        'wallet_credited' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Referrer
    |--------------------------------------------------------------------------
    */

    public function referrer()
    {
        return $this->belongsTo(
            Retailer::class,
            'referrer_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Referred Retailer
    |--------------------------------------------------------------------------
    */

    public function referred()
    {
        return $this->belongsTo(
            Retailer::class,
            'referred_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where(
            'status',
            'Pending'
        );
    }

    public function scopeApproved($query)
    {
        return $query->where(
            'status',
            'Approved'
        );
    }

    public function scopeReleased($query)
    {
        return $query->where(
            'wallet_credited',
            true
        );
    }

    public function scopeWaitingForRelease($query)
    {
        return $query->where(
                'status',
                'Approved'
            )
            ->where(
                'wallet_credited',
                false
            );
    }
}