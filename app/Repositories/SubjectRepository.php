<?php
namespace App\Repositories;

use App\Models\Subject;

class SubjectRepository
{
    public function all()
    {
        return Subject::with(['class','section'])->latest()->get();
    }

    public function getAll()
    {
        return Subject::all();
    }
    

    public function store($data)
    {
        return Subject::create($data);
    }

    public function update($id, $data)
    {
        $subject = Subject::findOrFail($id);
        $subject->update($data);
        return $subject;
    }

    public function delete($id)
    {
        return Subject::destroy($id);
    }
}