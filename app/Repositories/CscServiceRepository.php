<?php

namespace App\Repositories;

use App\Models\CscService;

class CscServiceRepository
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): CscService {

        return CscService::create(
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    public function find(
        int $id
    ): ?CscService {

        return CscService::find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND OR FAIL
    |--------------------------------------------------------------------------
    */

    public function findOrFail(
        int $id
    ): CscService {

        return CscService::findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY USER
    |--------------------------------------------------------------------------
    */

    public function findByUser(
        int $id,
        int $userId
    ): CscService {

        return CscService::where(
            'user_id',
            $userId
        )
        ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(
        int $userId
    ) {

        return CscService::where(
            'user_id',
            $userId
        )
        ->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        CscService $service
    ): bool {

        return $service->delete();
    }
}