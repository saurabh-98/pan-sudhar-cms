<?php

namespace App\Repositories;

use App\Models\District;

class DistrictRepository
{
    public function getAll()
    {
        return District::with('state')->latest()->get();
    }

    public function create(array $data): District
    {
        return District::create($data);
    }

    public function delete($id)
    {
        return District::findOrFail($id)->delete();
    }

    public function getByState($stateId)
    {
        return District::where('state_id', $stateId)
            ->where('status', 1)
            ->select('id', 'name') 
            ->orderBy('name')
            ->get();
    }
}