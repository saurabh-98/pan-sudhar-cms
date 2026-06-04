<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | RESET SPATIE CACHE
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS
        |--------------------------------------------------------------------------
        */

        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            'dashboard.view',

            /*
            |--------------------------------------------------------------------------
            | STATES
            |--------------------------------------------------------------------------
            */

            'states.view',
            'states.create',
            'states.edit',
            'states.delete',

            /*
            |--------------------------------------------------------------------------
            | DISTRICTS
            |--------------------------------------------------------------------------
            */

            'districts.view',
            'districts.create',
            'districts.edit',
            'districts.delete',

            /*
            |--------------------------------------------------------------------------
            | USERS
            |--------------------------------------------------------------------------
            */

            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            
            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */

            'profile.view',
            'profile.edit',
            'profile.password',

            /*
            |--------------------------------------------------------------------------
            | ROLES
            |--------------------------------------------------------------------------
            */

            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            /*
            |--------------------------------------------------------------------------
            | PERMISSIONS
            |--------------------------------------------------------------------------
            */

            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',

            /*
            |--------------------------------------------------------------------------
            | NOTICE
            |--------------------------------------------------------------------------
            */

            'notice.view',
            'notice.create',
            'notice.edit',
            'notice.delete',

            

          
            /*
            |--------------------------------------------------------------------------
            | CMS
            |--------------------------------------------------------------------------
            */

            'banners.view',
            'banners.create',
            'banners.edit',
            'banners.delete',

            'navigation.view',
            'navigation.create',
            'navigation.edit',
            'navigation.delete',

            'upi.view',
            'upi.create',
            'upi.edit',
            'upi.delete',
            'upi.activate',

            'pages.view',
            'pages.create',
            'pages.edit',
            'pages.delete',

            'footer.view',
            'footer.create',
            'footer.edit',
            'footer.delete',
            'footer.settings',

          
            /*
            |--------------------------------------------------------------------------
            | SETTINGS
            |--------------------------------------------------------------------------
            */

            'settings.view',
            'settings.edit',
            'settings.delete',


            /*
            |--------------------------------------------------------------------------
            | WALLET MANAGEMENT
            |--------------------------------------------------------------------------
            */

            'wallet.view',
            'wallet.add',
            'wallet.transactions',

            /*
            |--------------------------------------------------------------------------
            | NEW PAN MODULE
            |--------------------------------------------------------------------------
            */

            'pan.view',
            'pan.assign',



            /*
            |--------------------------------------------------------------------------
            | ITR MODULE
            |--------------------------------------------------------------------------
            */

            'itr.view',
            'itr.assign',
            'itr.status',
            'itr.delete',



            /*
            |--------------------------------------------------------------------------
            | ADMIN WALLET
            |--------------------------------------------------------------------------
            */

            'admin.wallet.view',
            'admin.wallet.add',
            'admin.wallet.withdraw',
            'admin.wallet.history',
            'admin.wallet.delete',

            /*
            |--------------------------------------------------------------------------
            | GALLERY
            |--------------------------------------------------------------------------
            */

            'gallery.view',
            'gallery.create',
            'gallery.edit',
            'gallery.delete',

            'retailer-approval.view',
            'retailer-approval.approve',
            'retailer-approval.reject',

            'modules.view',
            'modules.create',
            'modules.edit',
            'modules.delete',

            'charges.view',
            'charges.create',
            'charges.edit',
            'charges.delete'

        ];

        /*
        |--------------------------------------------------------------------------
        | CREATE ALL PERMISSIONS
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([

                'name' => $permission,

                'guard_name' => 'web',

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN ROLE
        |--------------------------------------------------------------------------
        |
        | Admin gets all permissions automatically.
        |
        */

        $adminRole = Role::firstOrCreate([

            'name' => 'admin',

            'guard_name' => 'web',

        ]);

        /*
        |--------------------------------------------------------------------------
        | RETAILER ROLE
        |--------------------------------------------------------------------------
        |
        | Permissions assigned manually from admin panel.
        |
        */

        Role::firstOrCreate([

            'name' => 'retailer',

            'guard_name' => 'web',

        ]);

        /*
        |--------------------------------------------------------------------------
        | BUSINESS DEVELOPMENT EXECUTIVE
        |--------------------------------------------------------------------------
        |
        | Permissions assigned manually from admin panel.
        |
        */

        Role::firstOrCreate([

            'name' => 'business-development-executive',

            'guard_name' => 'web',

        ]);

        /*
        |--------------------------------------------------------------------------
        | ADMIN GETS ALL PERMISSIONS
        |--------------------------------------------------------------------------
        */

        $adminRole->syncPermissions(

            Permission::all()

        );

        /*
        |--------------------------------------------------------------------------
        | ASSIGN ADMIN ROLE TO USER ID 1
        |--------------------------------------------------------------------------
        */

        $user = User::find(1);

        if ($user) {

            /*
            |--------------------------------------------------------------------------
            | REMOVE OLD ROLES
            |--------------------------------------------------------------------------
            */

            $user->syncRoles([]);

            /*
            |--------------------------------------------------------------------------
            | ASSIGN ADMIN ROLE
            |--------------------------------------------------------------------------
            */

            $user->assignRole('admin');
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR CACHE AGAIN
        |--------------------------------------------------------------------------
        */

        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();
    }
}