<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTransport extends Model
{
    protected $fillable = [
        'student_id',
        'route_id',
        'stop_id',
        'vehicle_id',
        'fee',
        'paid_amount',
        'due_amount',
        'status',
        'start_date'
    ];

    /* ================= RELATIONS ================= */

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class);
    }

    public function stop()
    {
        return $this->belongsTo(TransportStop::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}