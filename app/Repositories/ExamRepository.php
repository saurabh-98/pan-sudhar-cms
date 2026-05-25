<?php

namespace App\Repositories;

use App\Models\Exam;

class ExamRepository
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL EXAMS
    |--------------------------------------------------------------------------
    */

    public function all()
    {
        return Exam::with([

            'schoolClass:id,name',
            'section:id,name'

        ])

        ->latest()

        ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL
    |--------------------------------------------------------------------------
    */

    public function getAll()
    {
        return Exam::with([

            'schoolClass:id,name',
            'section:id,name'

        ])

        ->latest()

        ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store($data)
    {
        return Exam::create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update($id, $data)
    {
        $exam = Exam::findOrFail($id);

        $exam->update($data);

        return $exam;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        return Exam::destroy($id);
    }
}