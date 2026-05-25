<?php

namespace App\Repositories;

use App\Models\Leave;

class LeaveRepository
{
    /* =========================================================
     | ALL
     *=========================================================*/
    public function all()
    {
        return Leave::with([
                'employee',
                'approver'
            ])
            ->latest()
            ->get();
    }

    /* =========================================================
     | FIND
     *=========================================================*/
    public function find($id)
    {
        return Leave::with([
                'employee',
                'approver'
            ])
            ->findOrFail($id);
    }

    /* =========================================================
     | CREATE
     *=========================================================*/
    public function create(array $data)
    {
        return Leave::create($data);
    }

    /* =========================================================
     | UPDATE
     *=========================================================*/
    public function update($id, array $data)
    {
        $leave = $this->find($id);

        $leave->update($data);

        return $leave->fresh([
            'employee',
            'approver'
        ]);
    }

    /* =========================================================
     | DELETE
     *=========================================================*/
    public function delete($id)
    {
        $leave = $this->find($id);

        return $leave->delete();
    }

    /* =========================================================
     | UPDATE STATUS
     *=========================================================*/
    public function updateStatus(
        $id,
        $status,
        $remark = null
    ) {
        $leave = $this->find($id);

        $leave->update([

            'status'       => $status,

            'admin_remark' => $remark,

            'approved_at'  => now(),

            'approved_by'  => auth()->id(),
        ]);

        return $leave->fresh([
            'employee',
            'approver'
        ]);
    }

    /* =========================================================
     | GET BY STATUS
     *=========================================================*/
    public function getByStatus($status)
    {
        return Leave::with([
                'employee',
                'approver'
            ])
            ->where('status', $status)
            ->latest()
            ->get();
    }

    /* =========================================================
     | COUNT ALL
     *=========================================================*/
    public function count()
    {
        return Leave::count();
    }

    /* =========================================================
     | COUNT BY STATUS
     *=========================================================*/
    public function countByStatus($status)
    {
        return Leave::where(
            'status',
            $status
        )->count();
    }

    /* =========================================================
     | EMPLOYEE LEAVES
     *=========================================================*/
    public function getByEmployee($employeeId)
    {
        return Leave::with([
                'employee',
                'approver'
            ])
            ->where('employee_id', $employeeId)
            ->latest()
            ->get();
    }

    /* =========================================================
     | DATE RANGE
     *=========================================================*/
    public function getByDateRange(
        $fromDate,
        $toDate
    ) {
        return Leave::with([
                'employee',
                'approver'
            ])
            ->whereBetween('from_date', [
                $fromDate,
                $toDate
            ])
            ->latest()
            ->get();
    }
}