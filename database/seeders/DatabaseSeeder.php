<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | DISABLE FOREIGN KEY
        |--------------------------------------------------------------------------
        */

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        /*
        |--------------------------------------------------------------------------
        | RUN ALL SEEDERS
        |--------------------------------------------------------------------------
        */

      $this->call([

            /*
            |--------------------------------------------------------------------------
            | ROLES & PERMISSIONS
            |--------------------------------------------------------------------------
            */

            PermissionSeeder::class,
            RolePermissionSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | ROLES
            |--------------------------------------------------------------------------
            */

            ExecutiveRoleSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | USERS
            |--------------------------------------------------------------------------
            */

            AdminSeeder::class,
            DistributorSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | CMS
            |--------------------------------------------------------------------------
            */

            NavigationMenuSeeder::class,
            BannerSeeder::class,
            PageSeeder::class,

            ModuleSeeder::class,
            ChargeSeeder::class,
            ChargeCommissionSeeder::class,

        ]);

        /*
        |--------------------------------------------------------------------------
        | ENABLE FOREIGN KEY
        |--------------------------------------------------------------------------
        */

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}