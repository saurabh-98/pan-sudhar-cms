<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | RESET CACHE
        |--------------------------------------------------------------------------
        */

        app()[\Spatie\Permission\PermissionRegistrar::class]
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
            | MESSAGES
            |--------------------------------------------------------------------------
            */

            'messages.view',
            'messages.send',
            'messages.read',
            'messages.delete',

            /*
            |--------------------------------------------------------------------------
            | BANNERS
            |--------------------------------------------------------------------------
            */

            'banners.view',
            'banners.create',
            'banners.edit',
            'banners.delete',

            /*
            |--------------------------------------------------------------------------
            | NAVIGATION
            |--------------------------------------------------------------------------
            */

            'navigation.view',
            'navigation.create',
            'navigation.edit',
            'navigation.delete',

            /*
            |--------------------------------------------------------------------------
            | UPI
            |--------------------------------------------------------------------------
            */

            'upi.view',
            'upi.create',
            'upi.edit',
            'upi.delete',
            'upi.activate',

            /*
            |--------------------------------------------------------------------------
            | PAGES
            |--------------------------------------------------------------------------
            */

            'pages.view',
            'pages.create',
            'pages.edit',
            'pages.delete',

            /*
            |--------------------------------------------------------------------------
            | FOOTER
            |--------------------------------------------------------------------------
            */

            'footer.view',
            'footer.create',
            'footer.edit',
            'footer.delete',
            'footer.settings',

            
            /*
            |--------------------------------------------------------------------------
            | GALLERY
            |--------------------------------------------------------------------------
            */

            'gallery.view',
            'gallery.create',
            'gallery.edit',
            'gallery.delete',

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
            | SETTINGS
            |--------------------------------------------------------------------------
            */

            'settings.view',
            'settings.edit',

            'modules.view',
            'modules.create',
            'modules.edit',
            'modules.delete',
            
            'charges.view',
            'charges.create',
            'charges.edit',
            'charges.delete',

            'aadhaar.view',
            'aadhaar.assign',
            'aadhaar.status',
            'aadhaar.delete',
            'aadhaar.document.upload',
            
            'csc.view',
            'csc.assign',
            'csc.status',
            'csc.delete',
            'csc.document.upload',

            'voter-id.view',
            'voter-id.assign',
            'voter-id.status',
            'voter-id.delete',
            'voter-id.document.upload',

            'bank-account.view',
            'bank-account.assign',
            'bank-account.status',
            'bank-account.delete',
            'bank-account.upload',

            'other-service.view',
            'other-service.assign',
            'other-service.status',
            'other-service.delete',
            'other-service.upload'

            

            
        ];

        /*
        |--------------------------------------------------------------------------
        | CREATE PERMISSIONS
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {

            Permission::updateOrCreate(

                [
                    'name' => $permission,
                    'guard_name' => 'web'
                ]

            );
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT ROLES
        |--------------------------------------------------------------------------
        |
        | Admin gets all permissions automatically.
        | Retailer & BDO permissions will be managed
        | manually by admin from role panel.
        |
        */

        $roles = [

            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */

            'admin' => Permission::pluck('name')->toArray(),

            /*
            |--------------------------------------------------------------------------
            | RETAILER
            |--------------------------------------------------------------------------
            */

            'retailer' => [],

            /*
            |--------------------------------------------------------------------------
            | BUSINESS DEVELOPMENT EXECUTIVE
            |--------------------------------------------------------------------------
            */

            'business-development-executive' => [],

        ];

        /*
        |--------------------------------------------------------------------------
        | CREATE ROLES
        |--------------------------------------------------------------------------
        */

        foreach ($roles as $roleName => $rolePermissions) {

            /*
            |--------------------------------------------------------------------------
            | CREATE ROLE
            |--------------------------------------------------------------------------
            */

            $role = Role::updateOrCreate(

                [
                    'name' => $roleName,
                    'guard_name' => 'web'
                ]

            );

            /*
            |--------------------------------------------------------------------------
            | ADMIN AUTO PERMISSIONS
            |--------------------------------------------------------------------------
            */

            if (!empty($rolePermissions)) {

                $permissionsFromDB = Permission::whereIn(
                    'name',
                    $rolePermissions
                )->get();

                $role->syncPermissions(
                    $permissionsFromDB
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RESET CACHE AGAIN
        |--------------------------------------------------------------------------
        */

        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();
    }
}