<?php

namespace App\Repositories;

use App\Models\PanWithoutDocument;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PanApplyWithoutDocumentRepository
{
    public function create(
        array $data
    ): PanWithoutDocument {

        return PanWithoutDocument::create(
            $data
        );
    }


    public function history(
        int $userId
    ): LengthAwarePaginator {

        return PanWithoutDocument::query()

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
    ): PanWithoutDocument {

        return PanWithoutDocument::query()

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
        PanWithoutDocument $application,
        array $data
    ): bool {

        return $application->update(
            $data
        );
    }


    public function delete(
        PanWithoutDocument $application
    ): bool {

        return (bool) $application->delete();
    }
}