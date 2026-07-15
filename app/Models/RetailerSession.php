<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerSession extends Model
{
    protected $fillable = [
        'retailer_id',
        'login_at',
        'last_activity_at',
        'logout_at',
        'duration_seconds',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }
}