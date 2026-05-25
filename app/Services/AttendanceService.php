<?php

namespace App\Services;

use App\Repositories\AttendanceRepository;
use App\DTO\AttendanceDTO;

class AttendanceService
{
    protected $repo;

    public function __construct(
        AttendanceRepository $repo
    ) {
        $this->repo = $repo;
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function getStudents()
    {
        return $this->repo
            ->getStudentAttendance();
    }

    public function storeStudent(
        AttendanceDTO $dto
    ) {
        return $this->repo
            ->storeStudent(
                $dto->toStudentArray()
            );
    }

    public function updateStudent(
        $id,
        AttendanceDTO $dto
    ) {
        return $this->repo
            ->updateStudent(
                $id,
                $dto->toStudentArray()
            );
    }

    public function deleteStudent($id)
    {
        return $this->repo
            ->deleteStudent($id);
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function getTeachers()
    {
        return $this->repo
            ->getTeacherAttendance();
    }

    public function storeTeacher(
        AttendanceDTO $dto
    ) {
        return $this->repo
            ->storeTeacher(
                $dto->toTeacherArray()
            );
    }

    public function updateTeacher(
        $id,
        AttendanceDTO $dto
    ) {
        return $this->repo
            ->updateTeacher(
                $id,
                $dto->toTeacherArray()
            );
    }

    public function deleteTeacher($id)
    {
        return $this->repo
            ->deleteTeacher($id);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT DASHBOARD METHODS
    |--------------------------------------------------------------------------
    */

    public function getStudentAttendancePercentage(
        $studentId
    ) {
        return $this->repo
            ->getStudentAttendancePercentage(
                $studentId
            );
    }

    public function getStudentPresentDays(
        $studentId
    ) {
        return $this->repo
            ->getStudentPresentDays(
                $studentId
            );
    }

    public function getStudentAbsentDays(
        $studentId
    ) {
        return $this->repo
            ->getStudentAbsentDays(
                $studentId
            );
    }

    public function getStudentLateDays(
        $studentId
    ) {
        return $this->repo
            ->getStudentLateDays(
                $studentId
            );
    }

    public function getMonthlyAttendance(
        $studentId,
        $month = null,
        $year = null
    ) {

        return $this->repo
            ->getMonthlyAttendance(
                $studentId,
                $month,
                $year
            );
    }

    public function getTodayAttendance(
        $studentId
    ) {
        return $this->repo
            ->getTodayAttendance(
                $studentId
            );
    }

    public function getAttendanceStats(
        $studentId
    ) {

        return [

            'percentage' => $this
                ->getStudentAttendancePercentage(
                    $studentId
                ),

            'present' => $this
                ->getStudentPresentDays(
                    $studentId
                ),

            'absent' => $this
                ->getStudentAbsentDays(
                    $studentId
                ),

            'late' => $this
                ->getStudentLateDays(
                    $studentId
                ),

        ];
    }

    public function getAttendanceSummary(
        $studentId
    ) {

        $percentage = $this
            ->getStudentAttendancePercentage(
                $studentId
            );

        return [

            'percentage' => $percentage,

            'status' => match (true) {

                $percentage >= 90
                    => 'Excellent',

                $percentage >= 75
                    => 'Good',

                $percentage >= 50
                    => 'Average',

                default
                    => 'Poor'
            }

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER DASHBOARD METHODS
    |--------------------------------------------------------------------------
    */

    public function getTeacherAttendancePercentage(
        $teacherId
    ) {
        return $this->repo
            ->getTeacherAttendancePercentage(
                $teacherId
            );
    }

    public function getTeacherPresentDays(
        $teacherId
    ) {
        return $this->repo
            ->getTeacherPresentDays(
                $teacherId
            );
    }

    public function getTeacherAbsentDays(
        $teacherId
    ) {
        return $this->repo
            ->getTeacherAbsentDays(
                $teacherId
            );
    }

    public function getTeacherLateDays(
        $teacherId
    ) {
        return $this->repo
            ->getTeacherLateDays(
                $teacherId
            );
    }

    public function getTeacherMonthlyAttendance(
        $teacherId,
        $month = null,
        $year = null
    ) {

        return $this->repo
            ->getTeacherMonthlyAttendance(
                $teacherId,
                $month,
                $year
            );
    }

    public function getTodayTeacherAttendance(
        $teacherId
    ) {
        return $this->repo
            ->getTodayTeacherAttendance(
                $teacherId
            );
    }
}