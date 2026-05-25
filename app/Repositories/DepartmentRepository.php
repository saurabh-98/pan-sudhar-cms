<?php
namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository
{
    public function all()
    {
        return Department::latest()->get();
    }

    public function store(array $data)
    {
        return Department::create($data);
    }

    public function update($id, array $data)
    {
        return tap(Department::findOrFail($id))->update($data);
    }

    public function delete($id)
    {
        return Department::findOrFail($id)->delete();
    }
}