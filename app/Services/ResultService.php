<?php

namespace App\Services;

use App\Repositories\ResultRepository;

class ResultService
{
    public function __construct(
        protected ResultRepository $repo
    ) {}

    /*
    |--------------------------------------------------------------------------
    | ALL RESULTS
    |--------------------------------------------------------------------------
    */

    public function getResults()
    {
        $data = $this->repo->getResults();

        $data->getCollection()->transform(function ($item) {

            $percentage = $item->percentage;

            $item->grade = match (true) {

                $percentage >= 90 => 'A+',

                $percentage >= 75 => 'A',

                $percentage >= 60 => 'B',

                default => 'C'
            };

            $item->student_name = $item->student->name ?? '';

            $item->exam_name = $item->exam->name ?? '';

            return $item;
        });

        return $data;
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

        $results = $this->repo
            ->getLatestResults(
                $studentId,
                $limit
            );

        return $results->map(function ($item) {

            $percentage = $item->percentage;

            $item->grade = match (true) {

                $percentage >= 90 => 'A+',

                $percentage >= 75 => 'A',

                $percentage >= 60 => 'B',

                default => 'C'
            };

            $item->student_name =
                $item->student->name ?? '';

            $item->exam_name =
                $item->exam->name ?? '';

            return $item;
        });
    }

    public function getStudentAverage(
        $studentId
    ) {
        return $this->repo
            ->getStudentAverage($studentId);
    }

    public function getTopResults(
        $limit = 10
    ) {

        $results = $this->repo
            ->getTopResults($limit);

        return $results->map(function ($item) {

            $percentage = $item->percentage;

            $item->grade = match (true) {

                $percentage >= 90 => 'A+',

                $percentage >= 75 => 'A',

                $percentage >= 60 => 'B',

                default => 'C'
            };

            return $item;
        });
    }

    public function getStudentPassedSubjects(
        $studentId
    ) {
        return $this->repo
            ->getStudentPassedSubjects(
                $studentId
            );
    }

    public function getStudentFailedSubjects(
        $studentId
    ) {
        return $this->repo
            ->getStudentFailedSubjects(
                $studentId
            );
    }

    public function getStudentRank(
        $studentId
    ) {
        return $this->repo
            ->getStudentRank($studentId);
    }

    public function getRecentExams(
        $studentId,
        $limit = 5
    ) {
        return $this->repo
            ->getRecentExams(
                $studentId,
                $limit
            );
    }

    public function getClassTopper(
        $classId
    ) {
        return $this->repo
            ->getClassTopper($classId);
    }
}