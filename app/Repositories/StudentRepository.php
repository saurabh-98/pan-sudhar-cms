<?php
namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    public function all()
    {
        return Student::with(['class','section'])->latest()->get();
    }

    public function getAll()
    {
        return Student::all();
    }

    public function store($data)
    {
        return Student::create($data);
    }

    public function update($id,$data)
    {
        $s = Student::findOrFail($id);
        $s->update($data);
        return $s;
    }

    public function delete($id)
    {
        return Student::destroy($id);
    }
}