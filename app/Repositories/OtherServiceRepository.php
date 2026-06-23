<?php

namespace App\Repositories;

use App\Models\OtherService;

class OtherServiceRepository
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): OtherService {

        return OtherService::create(
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
    ): ?OtherService {

        return OtherService::find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND OR FAIL
    |--------------------------------------------------------------------------
    */

    public function findOrFail(
        int $id
    ): OtherService {

        return OtherService::findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY USER
    |--------------------------------------------------------------------------
    */

    public function findByUser(
        int $id,
        int $userId
    ): OtherService {

        return OtherService::where(
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

        return OtherService::where(
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
        OtherService $service
    ): bool {

        return $service->delete();
    }
}