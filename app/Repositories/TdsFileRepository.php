<?php

namespace App\Repositories;

use App\Models\TdsFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TdsFileRepository
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): TdsFile {

        return TdsFile::create(
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(
    int $userId
): LengthAwarePaginator {

    return TdsFile::query()

        ->with('documents')

        ->where(
            'user_id',
            $userId
        )

        ->latest()

        ->paginate(10);
}

    /*
    |--------------------------------------------------------------------------
    | FIND BY USER
    |--------------------------------------------------------------------------
    */

    public function findByUser(
        int $id,
        int $userId
    ): TdsFile {

        return TdsFile::query()

            ->where(
                'user_id',
                $userId
            )

            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): TdsFile {

        return TdsFile::findOrFail(
            $id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        TdsFile $tdsFile,
        array $data
    ): bool {

        unset(

            $data['aadhaar_front'],
            $data['aadhaar_back'],
            $data['pan_card']

        );

        return $tdsFile->update([

            'name' =>

                $data['name']
                ?? $tdsFile->name,

            'mobile' =>

                $data['mobile']
                ?? $tdsFile->mobile,

            'email' =>

                $data['email']
                ?? $tdsFile->email,

            'remarks' =>

                $data['remarks']
                ?? $tdsFile->remarks,

            'admin_remarks' =>

                $data['admin_remarks']
                ?? $tdsFile->admin_remarks,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        TdsFile $tdsFile
    ): bool {

        return $tdsFile->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        TdsFile $tdsFile,
        string $status
    ): bool {

        return $tdsFile->update([

            'status' => $status

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    public function updatePaymentStatus(
        TdsFile $tdsFile,
        string $status
    ): bool {

        return $tdsFile->update([

            'payment_status' => $status

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function totalApplications(
        int $userId
    ): int {

        return TdsFile::query()

            ->where(
                'user_id',
                $userId
            )

            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | PENDING APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function pendingApplications(
        int $userId
    ): int {

        return TdsFile::query()

            ->where(
                'user_id',
                $userId
            )

            ->where(
                'status',
                'Pending'
            )

            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function approvedApplications(
        int $userId
    ): int {

        return TdsFile::query()

            ->where(
                'user_id',
                $userId
            )

            ->where(
                'status',
                'Approved'
            )

            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | REJECTED APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function rejectedApplications(
        int $userId
    ): int {

        return TdsFile::query()

            ->where(
                'user_id',
                $userId
            )

            ->where(
                'status',
                'Rejected'
            )

            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | RECENT APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function recentApplications(
        int $userId,
        int $limit = 5
    ): Collection {

        return TdsFile::query()

            ->where(
                'user_id',
                $userId
            )

            ->latest()

            ->take($limit)

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    public function search(
        int $userId,
        ?string $keyword
    ): LengthAwarePaginator {

        return TdsFile::query()

            ->where(
                'user_id',
                $userId
            )

            ->when(

                $keyword,

                function ($query) use ($keyword) {

                    $query->where(

                        function ($q) use ($keyword) {

                            $q->where(
                                'application_no',
                                'LIKE',
                                "%{$keyword}%"
                            )

                            ->orWhere(
                                'name',
                                'LIKE',
                                "%{$keyword}%"
                            )

                            ->orWhere(
                                'mobile',
                                'LIKE',
                                "%{$keyword}%"
                            )

                            ->orWhere(
                                'email',
                                'LIKE',
                                "%{$keyword}%"
                            );

                        }

                    );

                }

            )

            ->latest()

            ->paginate(10);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN LIST
    |--------------------------------------------------------------------------
    */

    public function allPaginated(
        int $perPage = 20
    ): LengthAwarePaginator {

        return TdsFile::query()

            ->latest()

            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS COUNT
    |--------------------------------------------------------------------------
    */

    public function countByStatus(
        string $status
    ): int {

        return TdsFile::query()

            ->where(
                'status',
                $status
            )

            ->count();
    }
}