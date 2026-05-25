<?php

namespace App\Repositories;

use App\Models\Designation;

class DesignationRepository
{
    public function all()
    {
        return Designation::latest()->get();
    }

    public function create(array $data)
    {
        return Designation::create($data);
    }

    public function update(int $id, array $data)
    {
        $designation = $this->find($id);
        $designation->update($data);

        return $designation;
    }

    public function delete(int $id)
    {
        $designation = $this->find($id);
        return $designation->delete();
    }

    public function find(int $id)
    {
        return Designation::findOrFail($id);
    }
}