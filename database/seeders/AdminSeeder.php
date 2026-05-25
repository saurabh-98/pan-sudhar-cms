<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | ENSURE ADMIN ROLE EXISTS
        |--------------------------------------------------------------------------
        */

        Role::firstOrCreate([

            'name' => 'admin',

            'guard_name' => 'web',

        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE ADMIN USER
        |--------------------------------------------------------------------------
        */

        $admin = User::updateOrCreate(

            [
                'email' => 'admin@gmail.com'
            ],

            [


                'name' => 'Super Admin',

                'email' => strtolower(
                    trim('admin@gmail.com')
                ),

                'phone' => '9999999999',

                'password' => Hash::make(
                    'Admin@123'
                ),

                'status' => 1,

                'first_login' => 0,
            ]

        );

        /*
        |--------------------------------------------------------------------------
        | REMOVE OLD ROLES
        |--------------------------------------------------------------------------
        */

        $admin->syncRoles([]);

        /*
        |--------------------------------------------------------------------------
        | ASSIGN ADMIN ROLE
        |--------------------------------------------------------------------------
        */

        $admin->assignRole('admin');

        /*
        |--------------------------------------------------------------------------
        | SUCCESS MESSAGE
        |--------------------------------------------------------------------------
        */

        $this->command->info(

            'Admin user seeded successfully.'

        );
    }
}