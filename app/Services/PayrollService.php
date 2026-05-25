<?php

namespace App\Services;

use App\Repositories\PayrollRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeeAttendanceRepository;

class PayrollService
{
    public function __construct(
        protected PayrollRepository $payrollRepo,
        protected EmployeeRepository $employeeRepo,
        protected EmployeeAttendanceRepository $attendanceRepo
    ) {}

    /* ================= GET ALL ================= */
    public function getAll()
    {
        return $this->payrollRepo->all();
    }

    /* ================= GENERATE ================= */
    public function generate($dto)
    {
        $employees = $this->employeeRepo->all();

        foreach ($employees as $emp) {

            /* ================= ATTENDANCE ================= */
            $attendance = $this->attendanceRepo->getByEmployeeMonth(
                $emp->id,
                $dto->month,
                $dto->year
            );

            $absent = $attendance->where('status','Absent')->count();
            $late   = $attendance->where('status','Late')->count();

            /* ================= SALARY STRUCTURE ================= */
            $basic = $emp->basic_salary ?? 0;

            $hra   = $basic * 0.20;   // 20%
            $bonus = 1000;            // fixed (you can make dynamic)
            $pf    = $basic * 0.12;   // 12%

            /* ================= DAYS ================= */
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $dto->month, $dto->year);
            $perDay = $basic / $daysInMonth;

            /* ================= DEDUCTIONS ================= */
            $attendanceDeduction =
                ($absent * $perDay) +
                ($late * ($perDay * 0.5));

            $totalDeduction = $attendanceDeduction + $pf;

            /* ================= FINAL ================= */
            $gross = $basic + $hra + $bonus;

            $net = max(0, $gross - $totalDeduction);

            /* ================= SAVE ================= */
            $this->payrollRepo->save(
                $emp->id,
                $dto->month,
                $dto->year,
                [
                    'basic'        => $basic,
                    'hra'          => $hra,
                    'bonus'        => $bonus,
                    'pf'           => $pf,
                    'gross_salary' => $gross,
                    'deductions'   => $totalDeduction,
                    'net_salary'   => $net,
                    'status'       => 'Unpaid'
                ]
            );
        }
    }
    
    /* ================= MARK AS PAID ================= */
    public function markAsPaid($id)
    {
        $payroll = $this->payrollRepo->find($id);

        if ($payroll->status === 'Paid') {
            throw new \DomainException('Salary already paid');
        }

        return $this->payrollRepo->markAsPaid($id);
    }
}