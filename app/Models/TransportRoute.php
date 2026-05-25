<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    protected $fillable = [
        'name',
        'start_point',
        'end_point',
        'distance',
        'status',
        'description'
    ];

    /* ================= RELATIONS ================= */

    // 🛑 Route → Stops
    public function stops()
    {
        return $this->hasMany(TransportStop::class, 'route_id')
                    ->orderBy('stop_order');
    }

    // 🚌 Route → Vehicles
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'route_id');
    }

    // 🎓 Route → Student Transport
    public function students()
    {
        return $this->hasMany(StudentTransport::class, 'route_id');
    }

    /* ================= HELPERS ================= */

    // ✅ Only active vehicles
    public function activeVehicles()
    {
        return $this->vehicles()->where('status', 'active');
    }
}