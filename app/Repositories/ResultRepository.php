<?php

namespace App\Repositories;

use App\Models\Mark;

class ResultRepository
{
    /*
    |--------------------------------------------------------------------------
    | ALL RESULTS
    |--------------------------------------------------------------------------
    */

    public function getResults()
    {
        return Mark::selectRaw('

                student_id,
                exam_id,
                SUM(marks) as total,
                AVG(marks) as percentage

            ')
            ->with([

                'student:id,name',
                'exam:id,name'

            ])
            ->groupBy(
                'student_id',
                'exam_id'
            )
            ->paginate(20);
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD METHODS
    |--------------------------------------------------------------------------
    */

    public function getLatestResults(
        $studentId,
        $limit = 5
    ) {

        return Mark::selectRaw('

                student_id,
                exam_id,
                subject_id,
                SUM(marks) as total,
                AVG(marks) as percentage

            ')
            ->with([

                'student:id,name',
                'exam:id,name',
                'subject:id,name'

            ])
            ->where(
                'student_id',
                $studentId
            )
            ->groupBy(

                'student_id',
                'exam_id',
                'subject_id'

            )
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getStudentAverage(
        $studentId
    ) {

        return round(

            Mark::where(
                'student_id',
                $studentId
            )->avg('marks'),

            2

        );
    }

    public function getTopResults(
        $limit = 10
    ) {

        return Mark::selectRaw('

                student_id,
                exam_id,
                SUM(marks) as total,
                AVG(marks) as percentage

            ')
            ->with([

                'student:id,name',
                'exam:id,name'

            ])
            ->groupBy(
                'student_id',
                'exam_id'
            )
            ->orderByDesc('percentage')
            ->take($limit)
            ->get();
    }

    public function getStudentPassedSubjects(
        $studentId
    ) {

        return Mark::where(
            'student_id',
            $studentId
        )
        ->where(
            'marks',
            '>=',
            33
        )
        ->count();
    }

    public function getStudentFailedSubjects(
        $studentId
    ) {

        return Mark::where(
            'student_id',
            $studentId
        )
        ->where(
            'marks',
            '<',
            33
        )
        ->count();
    }

    public function getStudentRank(
        $studentId
    ) {

        $students = Mark::selectRaw('

                student_id,
                AVG(marks) as percentage

            ')
            ->groupBy('student_id')
            ->orderByDesc('percentage')
            ->get();

        $rank = 1;

        foreach ($students as $student) {

            if ($student->student_id == $studentId) {
                return $rank;
            }

            $rank++;
        }

        return null;
    }

    public function getRecentExams(
        $studentId,
        $limit = 5
    ) {

        return Mark::with([

                'exam:id,name',
                'subject:id,name'

            ])
            ->where(
                'student_id',
                $studentId
            )
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getClassTopper(
        $classId
    ) {

        return Mark::selectRaw('

                student_id,
                AVG(marks) as percentage

            ')
            ->with('student:id,name,class_id')
            ->whereHas('student', function ($q) use ($classId) {

                $q->where(
                    'class_id',
                    $classId
                );

            })
            ->groupBy('student_id')
            ->orderByDesc('percentage')
            ->first();
    }

    public function getTotalResults()
    {
        return Mark::count();
    }

    public function getTodayResults()
    {
        return Mark::whereDate(
            'created_at',
            today()
        )->count();
    }

    public function getAverageResultPercentage()
    {
        return round(
            Mark::avg('marks'),
            2
        );
    }

    public function getExamWiseResults(
        $examId
    ) {

        return Mark::with([

                'student:id,name',
                'subject:id,name',
                'exam:id,name'

            ])
            ->where(
                'exam_id',
                $examId
            )
            ->latest()
            ->get();
    }
}