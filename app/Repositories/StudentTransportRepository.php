<?php

namespace App\Repositories;

use App\Models\StudentTransport;
use App\Models\Vehicle;
use App\Models\TransportStop;
use App\Models\Student;
use App\Models\TransportRoute;

class StudentTransportRepository
{
    /* ================= LIST ================= */
    public function all()
    {
        return StudentTransport::with(['student','route','stop','vehicle'])
            ->latest()
            ->get();
    }

    /* ================= CREATE ================= */
    public function create(array $data)
    {
        return StudentTransport::create($data);
    }

    /* ================= UPDATE ================= */
    public function update($id, array $data)
    {
        $record = StudentTransport::findOrFail($id);
        $record->update($data);

        return $record;
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        return StudentTransport::findOrFail($id)->delete();
    }

    /* ================= CHECKS ================= */

    // 🚨 check if student already assigned
    public function existsForStudent($studentId)
    {
        return StudentTransport::where('student_id', $studentId)->exists();
    }

    // 🚨 count students in vehicle
    public function countByVehicle($vehicleId)
    {
        return StudentTransport::where('vehicle_id', $vehicleId)->count();
    }

    // 🚨 get vehicle capacity
    public function getVehicleCapacity($vehicleId)
    {
        return Vehicle::where('id', $vehicleId)->value('capacity');
    }

    /* ================= DROPDOWN DATA ================= */

    public function getStudents()
    {
        return Student::select('id','name')->get();
    }

    public function getRoutes()
    {
        return TransportRoute::select('id','name')->get();
    }

    public function getActiveVehicles()
    {
        return Vehicle::where(

                'status',

                'active'

            )
            ->select(

                'id',

                'vehicle_number',

                'vehicle_type',

                'driver_name',

                'driver_phone',

                'capacity',

                'gps_device_id'

            )
            ->get();
    }
    
    /* ================= EXTRA HELPERS ================= */

    // 🔥 get stops by route (dynamic dropdown)
    public function getStopsByRoute($routeId)
    {
        return TransportStop::where('route_id', $routeId)
            ->orderBy('stop_order')
            ->get();
    }

    // 🔥 available seats (bonus - for UI)
    public function getAvailableSeats($vehicleId)
    {
        $capacity = $this->getVehicleCapacity($vehicleId);
        $count = $this->countByVehicle($vehicleId);

        return $capacity - $count;
    }
}