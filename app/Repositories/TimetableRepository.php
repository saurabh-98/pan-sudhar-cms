<?php
namespace App\Repositories;

use App\Models\Timetable;

class TimetableRepository
{
    public function all()
    {
        return Timetable::with(['class','section','subject'])->latest()->get();
    }

    public function store($data)
    {
        return Timetable::create($data);
    }

    public function update($id, $data)
    {
        $t = Timetable::findOrFail($id);
        $t->update($data);
        return $t;
    }

    public function delete($id)
    {
        return Timetable::destroy($id);
    }
}