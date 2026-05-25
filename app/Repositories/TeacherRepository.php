<?php

namespace App\Repositories;

use App\Models\Teacher;

class TeacherRepository
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL
    |--------------------------------------------------------------------------
    */

    public function all()
    {
        return Teacher::with([

                'subjects:id,name',

                'classes:id,name'

            ])

            ->latest()

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    public function find($id)
    {
        return Teacher::with([

                'subjects:id,name',

                'classes:id,name'

            ])

            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(array $data)
    {
        return Teacher::create(

            $data

        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update($id, array $data)
    {
        $teacher = Teacher::findOrFail(

            $id

        );

        $teacher->update(

            $data

        );

        return $teacher->fresh([

            'subjects:id,name',

            'classes:id,name'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        return Teacher::destroy(

            $id

        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE TEACHERS
    |--------------------------------------------------------------------------
    */

    public function active()
    {
        return Teacher::where(

                'status',

                'active'

            )

            ->orderBy('name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL COUNT
    |--------------------------------------------------------------------------
    */

    public function count()
    {
        return Teacher::count();
    }
}