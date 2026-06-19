<?php

namespace App\Repositories;

use App\Models\VoterIdService;

class VoterIdServiceRepository
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): VoterIdService {

        return VoterIdService::create(
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
    ): ?VoterIdService {

        return VoterIdService::find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND OR FAIL
    |--------------------------------------------------------------------------
    */

    public function findOrFail(
        int $id
    ): VoterIdService {

        return VoterIdService::findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY USER
    |--------------------------------------------------------------------------
    */

    public function findByUser(
        int $id,
        int $userId
    ): VoterIdService {

        return VoterIdService::where(
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

        return VoterIdService::where(
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
        VoterIdService $service
    ): bool {

        return $service->delete();
    }
}