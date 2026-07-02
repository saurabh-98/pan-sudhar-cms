<?php

namespace App\Repositories;

use App\Models\ItrFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ItrFileRepository
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): ItrFile {

        return ItrFile::create(
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

    return ItrFile::query()

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
    ): ItrFile {

        return ItrFile::query()

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
    ): ItrFile {

        return ItrFile::findOrFail(
            $id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        ItrFile $itrFile,
        array $data
    ): bool {

        unset(

            $data['aadhaar_front'],
            $data['aadhaar_back'],
            $data['pan_card']

        );

        return $itrFile->update([

            'name' =>

                $data['name']
                ?? $itrFile->name,

            'mobile' =>

                $data['mobile']
                ?? $itrFile->mobile,

            'email' =>

                $data['email']
                ?? $itrFile->email,

            'remarks' =>

                $data['remarks']
                ?? $itrFile->remarks,

            'admin_remarks' =>

                $data['admin_remarks']
                ?? $itrFile->admin_remarks,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        ItrFile $itrFile
    ): bool {

        return $itrFile->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        ItrFile $itrFile,
        string $status
    ): bool {

        return $itrFile->update([

            'status' => $status

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    public function updatePaymentStatus(
        ItrFile $itrFile,
        string $status
    ): bool {

        return $itrFile->update([

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

        return ItrFile::query()

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

        return ItrFile::query()

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

        return ItrFile::query()

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

        return ItrFile::query()

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

        return ItrFile::query()

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

        return ItrFile::query()

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

        return ItrFile::query()

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

        return ItrFile::query()

            ->where(
                'status',
                $status
            )

            ->count();
    }
}