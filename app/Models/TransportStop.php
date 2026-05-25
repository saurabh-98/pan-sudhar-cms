<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportStop extends Model
{
    protected $fillable = [
        'route_id',
        'stop_name',
        'stop_order',
        'pickup_time'
    ];

    protected $casts = [
        'pickup_time' => 'datetime:H:i' // format time properly
    ];

    /* ================= RELATIONS ================= */

    // 🔗 belongs to route
    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    // 🔗 students at this stop
    public function students()
    {
        return $this->hasMany(StudentTransport::class, 'stop_id');
    }
}