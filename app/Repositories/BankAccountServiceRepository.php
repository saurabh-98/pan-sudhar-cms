<?php

namespace App\Repositories;

use App\Models\BankAccountService;

class BankAccountServiceRepository
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): BankAccountService {

        return BankAccountService::create(
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
    ): ?BankAccountService {

        return BankAccountService::find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND OR FAIL
    |--------------------------------------------------------------------------
    */

    public function findOrFail(
        int $id
    ): BankAccountService {

        return BankAccountService::findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY USER
    |--------------------------------------------------------------------------
    */

    public function findByUser(
        int $id,
        int $userId
    ): BankAccountService {

        return BankAccountService::where(
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

        return BankAccountService::where(
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
        BankAccountService $service
    ): bool {

        return $service->delete();
    }
}