<?php

namespace App\Repositories;

use App\Models\Vehicle;

class VehicleRepository
{
    public function all()
    {
        return Vehicle::with('route')->latest()->get();
    }

    public function create(array $data)
    {
        return Vehicle::create($data);
    }

    public function update($id, array $data)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($data);

        return $vehicle;
    }

    public function delete($id)
    {
        return Vehicle::findOrFail($id)->delete();
    }
}