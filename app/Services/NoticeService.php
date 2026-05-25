<?php

namespace App\Services;

use App\Repositories\NoticeRepository;

class NoticeService
{
    public function __construct(
        protected NoticeRepository $repo
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

    public function store($dto)
    {
        return $this->repo->store(
            $dto->toArray()
        );
    }

    public function update($id, $dto)
    {
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

    public function getStudentNoticeCount($classId = null)
    {
        return $this->repo
            ->getStudentNoticeCount($classId);
    }

    public function getTeacherNoticeCount()
    {
        return $this->repo
            ->getTeacherNoticeCount();
    }

    public function getLatestNotices($limit = 5)
    {
        return $this->repo
            ->getLatestNotices($limit);
    }

    public function getStudentNotices($classId = null)
    {
        return $this->repo
            ->getStudentNotices($classId);
    }

    public function getTeacherNotices()
    {
        return $this->repo
            ->getTeacherNotices();
    }

    public function getTodayNotices()
    {
        return $this->repo
            ->getTodayNotices();
    }

    public function getImportantNotices()
    {
        return $this->repo
            ->getImportantNotices();
    }

    public function getActiveNotices()
    {
        return $this->repo
            ->getActiveNotices();
    }

    public function getExpiredNotices()
    {
        return $this->repo
            ->getExpiredNotices();
    }

    public function getRecentNoticeCount($days = 7)
    {
        return $this->repo
            ->getRecentNoticeCount($days);
    }
}