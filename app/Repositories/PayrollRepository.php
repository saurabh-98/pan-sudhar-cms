<?php

namespace App\Repositories;

use App\Models\Payroll;

class PayrollRepository
{
    /* ================= GET ALL ================= */
    public function all()
    {
        return Payroll::with('employee')
            ->latest()
            ->paginate(10);
    }

    /* ================= FIND ================= */
    public function find($id)
    {
        return Payroll::findOrFail($id);
    }

    /* ================= SAVE / UPDATE ================= */
    public function save($empId, $month, $year, $data)
    {
        return Payroll::updateOrCreate(
            [
                'employee_id' => $empId,
                'month' => $month,
                'year' => $year
            ],
            $data
        );
    }

    /* ================= UPDATE ================= */
    public function update($id, $data)
    {
        $payroll = $this->find($id);
        $payroll->update($data);

        return $payroll;
    }

    /* ================= MARK PAID ================= */
    public function markAsPaid($id)
    {
        $payroll = $this->find($id);

        $payroll->update([
            'status' => 'Paid'
        ]);

        return $payroll;
    }

    public function findWithEmployee($id)
    {
        return Payroll::with('employee')->findOrFail($id);
    }
}