<?php

namespace App\Repositories;

use App\Models\StudentAttendance;
use App\Models\TeacherAttendance;

class AttendanceRepository
{

    /*
    |--------------------------------------------------------------------------
    | STUDENT ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function getStudentAttendance()
    {
        return StudentAttendance::with('student')
            ->latest()
            ->get();
    }

    public function storeStudent($data)
    {
        return StudentAttendance::updateOrCreate(

            [
                'student_id' => $data['student_id'],
                'date'       => $data['date']
            ],

            [
                'status'     => $data['status']
            ]

        );
    }

    public function updateStudent($id, $data)
    {
        $attendance = StudentAttendance::findOrFail($id);

        $attendance->update($data);

        return $attendance;
    }

    public function deleteStudent($id)
    {
        return StudentAttendance::destroy($id);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT DASHBOARD METHODS
    |--------------------------------------------------------------------------
    */

    public function getStudentAttendancePercentage($studentId)
    {
        $totalAttendance = StudentAttendance::where(
            'student_id',
            $studentId
        )->count();

        if ($totalAttendance == 0) {
            return 0;
        }

        $presentAttendance = StudentAttendance::where(
            'student_id',
            $studentId
        )
        ->where('status', 'present')
        ->count();

        return round(
            ($presentAttendance / $totalAttendance) * 100
        );
    }

    public function getStudentPresentDays($studentId)
    {
        return StudentAttendance::where(
            'student_id',
            $studentId
        )
        ->where('status', 'present')
        ->count();
    }

    public function getStudentAbsentDays($studentId)
    {
        return StudentAttendance::where(
            'student_id',
            $studentId
        )
        ->where('status', 'absent')
        ->count();
    }

    public function getStudentLateDays($studentId)
    {
        return StudentAttendance::where(
            'student_id',
            $studentId
        )
        ->where('status', 'late')
        ->count();
    }

    public function getMonthlyAttendance(
        $studentId,
        $month = null,
        $year = null
    ) {

        $month = $month ?? now()->month;

        $year  = $year ?? now()->year;

        return StudentAttendance::where(
            'student_id',
            $studentId
        )
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->latest()
        ->get();
    }

    public function getTodayAttendance($studentId)
    {
        return StudentAttendance::where(
            'student_id',
            $studentId
        )
        ->whereDate('date', today())
        ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function getTeacherAttendance()
    {
        return TeacherAttendance::with('teacher')
            ->latest()
            ->get();
    }

    public function storeTeacher($data)
    {
        return TeacherAttendance::updateOrCreate(

            [
                'teacher_id' => $data['teacher_id'],
                'date'       => $data['date']
            ],

            [
                'status'     => $data['status']
            ]

        );
    }

    public function updateTeacher($id, $data)
    {
        $attendance = TeacherAttendance::findOrFail($id);

        $attendance->update($data);

        return $attendance;
    }

    public function deleteTeacher($id)
    {
        return TeacherAttendance::destroy($id);
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER DASHBOARD METHODS
    |--------------------------------------------------------------------------
    */

    public function getTeacherAttendancePercentage($teacherId)
    {
        $totalAttendance = TeacherAttendance::where(
            'teacher_id',
            $teacherId
        )->count();

        if ($totalAttendance == 0) {
            return 0;
        }

        $presentAttendance = TeacherAttendance::where(
            'teacher_id',
            $teacherId
        )
        ->where('status', 'present')
        ->count();

        return round(
            ($presentAttendance / $totalAttendance) * 100
        );
    }

    public function getTeacherPresentDays($teacherId)
    {
        return TeacherAttendance::where(
            'teacher_id',
            $teacherId
        )
        ->where('status', 'present')
        ->count();
    }

    public function getTeacherAbsentDays($teacherId)
    {
        return TeacherAttendance::where(
            'teacher_id',
            $teacherId
        )
        ->where('status', 'absent')
        ->count();
    }

    public function getTeacherLateDays($teacherId)
    {
        return TeacherAttendance::where(
            'teacher_id',
            $teacherId
        )
        ->where('status', 'late')
        ->count();
    }

    public function getTeacherMonthlyAttendance(
        $teacherId,
        $month = null,
        $year = null
    ) {

        $month = $month ?? now()->month;

        $year  = $year ?? now()->year;

        return TeacherAttendance::where(
            'teacher_id',
            $teacherId
        )
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->latest()
        ->get();
    }

    public function getTodayTeacherAttendance($teacherId)
    {
        return TeacherAttendance::where(
            'teacher_id',
            $teacherId
        )
        ->whereDate('date', today())
        ->first();
    }
}