<?php

namespace App\Repositories;

use App\Models\Homework;

class HomeworkRepository
{
    /*
    |--------------------------------------------------------------------------
    | CRUD METHODS
    |--------------------------------------------------------------------------
    */

    public function getAll()
    {
        return Homework::with([

                'class:id,name',
                'subject:id,name'

            ])
            ->latest()
            ->paginate(20);
    }

    public function store($data)
    {
        return Homework::create($data);
    }

    public function update($id, $data)
    {
        $hw = Homework::findOrFail($id);

        $hw->update($data);

        return $hw;
    }

    public function delete($id)
    {
        return Homework::destroy($id);
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD METHODS
    |--------------------------------------------------------------------------
    */

    public function getPendingHomeworkCount($classId)
    {
        return Homework::where(
            'class_id',
            $classId
        )
        ->whereDate(
            'due_date',
            '>=',
            today()
        )
        ->count();
    }

    public function getCompletedHomeworkCount($studentId)
    {
        return Homework::where(
            'student_id',
            $studentId
        )
        ->where('status', 'completed')
        ->count();
    }

    public function getStudentHomework($studentId)
    {
        return Homework::with([

                'class:id,name',
                'subject:id,name'

            ])
            ->where(
                'student_id',
                $studentId
            )
            ->latest()
            ->get();
    }

    public function getTodayHomework($studentId)
    {
        return Homework::with([

                'class:id,name',
                'subject:id,name'

            ])
            ->where(
                'student_id',
                $studentId
            )
            ->whereDate(
                'created_at',
                today()
            )
            ->latest()
            ->get();
    }

    public function getUpcomingHomework($studentId)
    {
        return Homework::with([

                'class:id,name',
                'subject:id,name'

            ])
            ->where(
                'student_id',
                $studentId
            )
            ->whereDate(
                'due_date',
                '>=',
                today()
            )
            ->latest()
            ->get();
    }

    public function getRecentHomework($limit = 5)
    {
        return Homework::with([

                'class:id,name',
                'subject:id,name'

            ])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getClassHomework($classId)
    {
        return Homework::with([

                'class:id,name',
                'subject:id,name'

            ])
            ->where(
                'class_id',
                $classId
            )
            ->latest()
            ->get();
    }

    public function getSubjectHomework(
        $classId,
        $subjectId
    ) {
        return Homework::with([

                'class:id,name',
                'subject:id,name'

            ])
            ->where(
                'class_id',
                $classId
            )
            ->where(
                'subject_id',
                $subjectId
            )
            ->latest()
            ->get();
    }

    public function getTotalHomework()
    {
        return Homework::count();
    }

    public function getTodayHomeworkCount()
    {
        return Homework::whereDate(
            'created_at',
            today()
        )->count();
    }

    public function getPendingHomework()
    {
        return Homework::where(
            'status',
            'pending'
        )->count();
    }

    public function getCompletedHomework()
    {
        return Homework::where(
            'status',
            'completed'
        )->count();
    }
}