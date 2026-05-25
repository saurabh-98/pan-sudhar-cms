<?php

namespace App\Repositories;

use App\Models\Notice;
use Illuminate\Support\Facades\Schema;

class NoticeRepository
{
    /*
    |--------------------------------------------------------------------------
    | CRUD METHODS
    |--------------------------------------------------------------------------
    */

    public function getAll()
    {
        return Notice::latest()
            ->paginate(20);
    }

    public function store($data)
    {
        return Notice::create($data);
    }

    public function update($id, $data)
    {
        $notice = Notice::findOrFail($id);

        $notice->update($data);

        return $notice;
    }

    public function delete($id)
    {
        return Notice::destroy($id);
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD METHODS
    |--------------------------------------------------------------------------
    */

    public function getStudentNoticeCount($classId = null)
    {
        $query = Notice::query();

        /*
        |--------------------------------------------------------------------------
        | APPLY CLASS FILTER ONLY IF COLUMN EXISTS
        |--------------------------------------------------------------------------
        */

        if (
            $classId &&
            Schema::hasColumn('notices', 'class_id')
        ) {

            $query->where(
                'class_id',
                $classId
            );
        }

        return $query->count();
    }

    public function getTeacherNoticeCount()
    {
        return Notice::count();
    }

    public function getLatestNotices($limit = 5)
    {
        return Notice::latest()
            ->take($limit)
            ->get();
    }

    public function getStudentNotices($classId = null)
    {
        $query = Notice::query();

        /*
        |--------------------------------------------------------------------------
        | APPLY CLASS FILTER ONLY IF COLUMN EXISTS
        |--------------------------------------------------------------------------
        */

        if (
            $classId &&
            Schema::hasColumn('notices', 'class_id')
        ) {

            $query->where(
                'class_id',
                $classId
            );
        }

        return $query
            ->latest()
            ->get();
    }

    public function getTeacherNotices()
    {
        return Notice::latest()
            ->get();
    }

    public function getTodayNotices()
    {
        return Notice::whereDate(
                'created_at',
                today()
            )
            ->latest()
            ->get();
    }

    public function getImportantNotices()
    {
        /*
        |--------------------------------------------------------------------------
        | CHECK priority COLUMN
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn(
                'notices',
                'priority'
            )
        ) {

            return Notice::where(
                    'priority',
                    'high'
                )
                ->latest()
                ->get();
        }

        return collect([]);
    }

    public function getActiveNotices()
    {
        /*
        |--------------------------------------------------------------------------
        | CHECK status COLUMN
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn(
                'notices',
                'status'
            )
        ) {

            return Notice::where(
                    'status',
                    'active'
                )
                ->latest()
                ->get();
        }

        return Notice::latest()->get();
    }

    public function getExpiredNotices()
    {
        /*
        |--------------------------------------------------------------------------
        | CHECK expiry_date COLUMN
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn(
                'notices',
                'expiry_date'
            )
        ) {

            return Notice::whereDate(
                    'expiry_date',
                    '<',
                    today()
                )
                ->latest()
                ->get();
        }

        return collect([]);
    }

    public function getRecentNoticeCount($days = 7)
    {
        return Notice::where(
                'created_at',
                '>=',
                now()->subDays($days)
            )
            ->count();
    }
}