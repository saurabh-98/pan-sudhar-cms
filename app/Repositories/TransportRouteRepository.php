<?php
// app/Repositories/TransportRouteRepository.php

namespace App\Repositories;

use App\Models\TransportRoute;

class TransportRouteRepository
{
    public function create(array $data)
    {
        return TransportRoute::create($data);
    }

    public function all()
    {
        return TransportRoute::latest()->get();
    }

    public function find($id)
    {
        return TransportRoute::findOrFail($id);
    }

    public function allWithRelations()
    {
        return TransportRoute::with(['stops', 'vehicles'])
            ->latest()
            ->get();
    }

    public function update($id, array $data)
    {
        $route = $this->find($id);
        $route->update($data);
        return $route;
    }

    public function delete($id)
    {
        return TransportRoute::destroy($id);
    }
}