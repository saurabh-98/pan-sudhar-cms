<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'vehicle_number',
        'vehicle_type',
        'driver_name',
        'driver_phone',
        'helper_name',
        'helper_phone',
        'capacity',
        'status',
        'gps_device_id',
        'route_id'
    ];

    /* ================= RELATIONS ================= */

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function students()
    {
        return $this->hasMany(StudentTransport::class, 'vehicle_id');
    }
}