<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | PRINCIPAL
        |--------------------------------------------------------------------------
        */

        $principal = User::updateOrCreate(

            [
                'email' => 'principal@test.com'
            ],

            [
                'name' => 'School Principal',

                'email' => 'principal@test.com',

                'password' => Hash::make('12345678'),

                'status' => 1,
            ]

        );

        $principal->assignRole('principal');

        /*
        |--------------------------------------------------------------------------
        | VICE PRINCIPAL
        |--------------------------------------------------------------------------
        */

        $vicePrincipal = User::updateOrCreate(

            [
                'email' => 'viceprincipal@test.com'
            ],

            [
                'name' => 'Vice Principal',

                'email' => 'viceprincipal@test.com',

                'password' => Hash::make('12345678'),

                'status' => 1,
            ]

        );

        $vicePrincipal->assignRole('vice-principal');

        /*
        |--------------------------------------------------------------------------
        | TEACHER
        |--------------------------------------------------------------------------
        */

        $teacher = User::updateOrCreate(

            [
                'email' => 'teacher@test.com'
            ],

            [
                'name' => 'School Teacher',

                'email' => 'teacher@test.com',

                'password' => Hash::make('12345678'),

                'status' => 1,
            ]

        );

        $teacher->assignRole('teacher');

        /*
        |--------------------------------------------------------------------------
        | RECEPTIONIST
        |--------------------------------------------------------------------------
        */

        $receptionist = User::updateOrCreate(

            [
                'email' => 'reception@test.com'
            ],

            [
                'name' => 'Receptionist',

                'email' => 'reception@test.com',

                'password' => Hash::make('12345678'),

                'status' => 1,
            ]

        );

        $receptionist->assignRole('receptionist');

        /*
        |--------------------------------------------------------------------------
        | HR MANAGER
        |--------------------------------------------------------------------------
        */

        $hr = User::updateOrCreate(

            [
                'email' => 'hr@test.com'
            ],

            [
                'name' => 'HR Manager',

                'email' => 'hr@test.com',

                'password' => Hash::make('12345678'),

                'status' => 1,
            ]

        );

        $hr->assignRole('hr-manager');

        /*
        |--------------------------------------------------------------------------
        | LIBRARY MANAGER
        |--------------------------------------------------------------------------
        */

        $libraryManager = User::updateOrCreate(

            [
                'email' => 'library@test.com'
            ],

            [
                'name' => 'Library Manager',

                'email' => 'library@test.com',

                'password' => Hash::make('12345678'),

                'status' => 1,
            ]

        );

        $libraryManager->assignRole('library-manager');

        /*
        |--------------------------------------------------------------------------
        | TRANSPORT MANAGER
        |--------------------------------------------------------------------------
        */

        $transport = User::updateOrCreate(

            [
                'email' => 'transport@test.com'
            ],

            [
                'name' => 'Transport Manager',

                'email' => 'transport@test.com',

                'password' => Hash::make('12345678'),

                'status' => 1,
            ]

        );

        $transport->assignRole('transport-manager');
    }
}