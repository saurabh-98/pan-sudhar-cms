<?php

namespace App\Services;

use App\DTO\EmployeeAttendanceDTO;
use App\Repositories\EmployeeAttendanceRepository;

class EmployeeAttendanceService
{
    public function __construct(
        protected EmployeeAttendanceRepository $repo
    ) {}

    public function mark(EmployeeAttendanceDTO $dto)
    {
        foreach ($dto->records as $row) {

            $this->repo->mark(
                $row['employee_id'],
                $row['date'],
                $row['status']
            );
        }
    }

    public function getByDate($date)
    {
        return $this->repo->getByDate($date);
    }
}