<?php

namespace App\Repositories;

use App\Models\AadhaarService;

class AadhaarServiceRepository
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): AadhaarService {

        return AadhaarService::create(
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
    ): ?AadhaarService {

        return AadhaarService::find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND OR FAIL
    |--------------------------------------------------------------------------
    */

    public function findOrFail(
        int $id
    ): AadhaarService {

        return AadhaarService::findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY USER
    |--------------------------------------------------------------------------
    */

    public function findByUser(
        int $id,
        int $userId
    ): AadhaarService {

        return AadhaarService::where(
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

        return AadhaarService::where(
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
        AadhaarService $service
    ): bool {

        return $service->delete();
    }
}