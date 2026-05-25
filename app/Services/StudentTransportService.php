<?php

namespace App\Services;

use App\DTO\StudentTransportDTO;
use App\Repositories\StudentTransportRepository;

class StudentTransportService
{
    public function __construct(
        protected StudentTransportRepository $repo
    ) {}

    /* ================= LIST ================= */
    public function getAll()
    {
        return $this->repo->all();
    }

    /* ================= DROPDOWNS ================= */

    public function getStudents()
    {
        return $this->repo->getStudents();
    }

    public function getRoutes()
    {
        return $this->repo->getRoutes();
    }

    public function getActiveVehicles()
    {
        return $this->repo->getActiveVehicles();
    }

    public function getStopsByRoute($route_id)
    {
        return $this->repo->getStopsByRoute($route_id);
    }

    /* ================= ASSIGN ================= */

    public function assign(StudentTransportDTO $dto)
    {
        // 🚨 Duplicate check
        if ($this->repo->existsForStudent($dto->student_id)) {
            throw new \Exception('Student already assigned transport');
        }

        // 🚨 Capacity check
        $capacity = $this->repo->getVehicleCapacity($dto->vehicle_id);

        if (!$capacity) {
            throw new \Exception('Invalid vehicle selected');
        }

        $count = $this->repo->countByVehicle($dto->vehicle_id);

        if ($count >= $capacity) {
            throw new \Exception('Vehicle is Full');
        }

        return $this->repo->create($dto->toArray());
    }

    /* ================= UPDATE ================= */
    public function update($id, StudentTransportDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}