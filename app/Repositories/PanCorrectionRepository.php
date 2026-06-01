<?php

namespace App\Repositories;

use App\Models\PanCorrectionApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PanCorrectionRepository
{
    public function create(
        array $data
    ): PanCorrectionApplication {

        return PanCorrectionApplication::create(
            $data
        );
    }


    public function history(
        int $userId
    ): LengthAwarePaginator {

        return PanCorrectionApplication::query()

            ->where(
                'user_id',
                $userId
            )

            ->latest()

            ->paginate(10);
    }


    public function findByUser(
        int $id,
        int $userId
    ): PanCorrectionApplication {

        return PanCorrectionApplication::query()

            ->where(
                'id',
                $id
            )

            ->where(
                'user_id',
                $userId
            )

            ->firstOrFail();
    }


    public function update(
        PanCorrectionApplication $application,
        array $data
    ): bool {

        return $application->update(
            $data
        );
    }


    public function delete(
        PanCorrectionApplication $application
    ): bool {

        return (bool) $application->delete();
    }
}