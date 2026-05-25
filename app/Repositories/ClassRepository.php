<?php
namespace App\Repositories;

use App\Models\SchoolClass;

class ClassRepository
{
    public function all()
    {
        return SchoolClass::latest()->get();
    }

    public function store($data)
    {
        return SchoolClass::create($data);
    }

    public function find($id)
    {
        return SchoolClass::findOrFail($id);
    }

    public function update($id, $data)
    {
        $class = $this->find($id);
        $class->update($data);
        return $class;
    }

    public function delete($id)
    {
        return SchoolClass::destroy($id);
    }
}