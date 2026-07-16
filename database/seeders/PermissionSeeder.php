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
           /*
            |--------------------------------------------------------------------------
            | WALLET MANAGEMENT
            |--------------------------------------------------------------------------
            */

            'wallet.view',

            'wallet.add',

            'wallet.deduct',

            'wallet.transactions',

            'payment.requests.view',

            'payment.requests.approve',

            'payment.requests.reject',

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
            | TDS MODULE
            |--------------------------------------------------------------------------
            */

            'tds.view',
            'tds.assign',
            'tds.status',
            'tds.delete',




           

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
            'other-service.upload',
            
            'service-guidelines.view',
            'service-guidelines.create',
            'service-guidelines.edit',
            'service-guidelines.delete',

            'bank-docs.view',
            'bank-docs.create',
            'bank-docs.edit',
            'bank-docs.delete',

            'pan-find.view',
            'pan-find.assign',
            'pan-find.status',
            'pan-find.delete',
            'pan-find.upload',
            'pan-find.reject',

            'retailer-activity.view',

            'chat.view',
            'chat.reply',
            'chat.assign',
            'chat.close',
            'chat.delete',
            
            'popup-announcements.view',
            'popup-announcements.create',
            'popup-announcements.edit',
            'popup-announcements.delete',


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