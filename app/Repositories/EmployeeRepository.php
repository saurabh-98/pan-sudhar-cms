<?php
namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    public function all()
    {
        return Employee::with(['department','designation'])->get();
    }

    public function store($data)
    {
        return Employee::create($data);
    }

    public function update($id,$data)
    {
        return tap(Employee::findOrFail($id))->update($data);
    }

    public function delete($id)
    {
        return Employee::findOrFail($id)->delete();
    }
}