<?php

namespace App\Services;

use App\Repositories\HomeworkRepository;
use App\DTO\HomeworkDTO;

class HomeworkService
{
    public function __construct(
        protected HomeworkRepository $repo
    ) {}

    /*
    |--------------------------------------------------------------------------
    | CRUD METHODS
    |--------------------------------------------------------------------------
    */

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function store(
        HomeworkDTO $dto
    ) {
        return $this->repo->store(
            $dto->toArray()
        );
    }

    public function update(
        $id,
        HomeworkDTO $dto
    ) {
        return $this->repo->update(
            $id,
            $dto->toArray()
        );
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD METHODS
    |--------------------------------------------------------------------------
    */

    public function getPendingHomeworkCount($classId)
    {
        return $this->repo
            ->getPendingHomeworkCount($classId);
    }
    
    public function getCompletedHomeworkCount($studentId)
    {
        return $this->repo
            ->getCompletedHomeworkCount($studentId);
    }

    public function getStudentHomework($studentId)
    {
        return $this->repo
            ->getStudentHomework($studentId);
    }

    public function getTodayHomework($studentId)
    {
        return $this->repo
            ->getTodayHomework($studentId);
    }

    public function getUpcomingHomework($studentId)
    {
        return $this->repo
            ->getUpcomingHomework($studentId);
    }

    public function getRecentHomework($limit = 5)
    {
        return $this->repo
            ->getRecentHomework($limit);
    }

    public function getClassHomework($classId)
    {
        return $this->repo
            ->getClassHomework($classId);
    }

    public function getSubjectHomework(
        $classId,
        $subjectId
    ) {
        return $this->repo
            ->getSubjectHomework(
                $classId,
                $subjectId
            );
    }
}