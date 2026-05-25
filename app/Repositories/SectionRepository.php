<?php

namespace App\Repositories;

use App\Models\Section;

class SectionRepository
{
    /* ================= ALL ================= */
    public function all()
    {
        return Section::with('classroom')->latest()->get();
    }

    /* ================= STORE (NO DUPLICATE) ================= */
    public function store($data)
    {
        return Section::firstOrCreate(
            [
                'class_id' => $data['class_id'],
                'name'     => strtoupper($data['name']) // normalize
            ],
            $data
        );
    }

    /* ================= UPDATE ================= */
    public function update($id, $data)
    {
        $section = Section::findOrFail($id);

        $section->update([
            'name' => strtoupper($data['name']), // normalize
        ]);

        return $section;
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        return Section::destroy($id);
    }

    /* ================= GET BY CLASS (CLEAN) ================= */
    public function getByClass($class_id)
    {
        return Section::where('class_id', $class_id)
            ->orderBy('name')
            ->get()
            ->unique('name') // remove duplicate names
            ->values();
    }

    /* ================= WITH STUDENT COUNT ================= */
    public function getByClassWithCount($class_id)
    {
        return Section::where('class_id', $class_id)
            ->withCount('students')
            ->orderBy('name')
            ->get()
            ->filter(function ($section) {
                return $section->students_count < $section->capacity; 
            })
            ->values();
    }

}