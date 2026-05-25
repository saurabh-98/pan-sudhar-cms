<?php

namespace App\Repositories;

use App\Models\Attendance;

class EmployeeAttendanceRepository
{
    public function mark($employeeId, $date, $status)
    {
        return Attendance::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'date' => $date
            ],
            [
                'status'    => $status,
                'check_in'  => in_array($status, ['Present','Late']) ? now() : null,
                'check_out' => null
            ]
        );
    }

    /* ================= GET BY DATE ================= */
    public function getByDate($date)
    {
        return Attendance::whereDate('date', $date)
            ->get()
            ->keyBy('employee_id'); 
    }

    public function getByEmployeeMonth($employeeId, $month, $year)
    {
        return Attendance::where('employee_id', $employeeId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
    }
}