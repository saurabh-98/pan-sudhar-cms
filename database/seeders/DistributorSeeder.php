<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DistributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CREATE DISTRIBUTOR ROLE
        |--------------------------------------------------------------------------
        */

        Role::firstOrCreate([
            'name' => 'Distributor',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE DISTRIBUTOR USER
        |--------------------------------------------------------------------------
        */

        $user = User::firstOrCreate(

            [
                'email' => 'distributor@example.com',
            ],

            [
                'name' => 'Default Distributor',

                'mobile' => '9999999999',

                'password' => Hash::make('password'),

                'status' => 1,

                'first_login' => 0,

                'wallet_balance' => 0,
            ]

        );

        /*
        |--------------------------------------------------------------------------
        | ASSIGN ROLE
        |--------------------------------------------------------------------------
        */

        if (!$user->hasRole('Distributor')) {

            $user->assignRole('Distributor');

        }
    }
}