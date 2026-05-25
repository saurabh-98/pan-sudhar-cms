<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | AJAX DATATABLE
        |--------------------------------------------------------------------------
        */

        if (request()->ajax()) {

            $roles = Role::with('permissions')
                ->latest()
                ->get();

            return response()->json($roles);
        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        $roles = Role::with('permissions')
            ->latest()
            ->get();

        $permissions = Permission::all();

        return view(
            'admin.roles.index',
            compact(
                'roles',
                'permissions'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $permissions = Permission::all();

        return view(
            'admin.roles.create',
            compact('permissions')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|unique:roles,name',

            'permissions' => 'nullable|array',
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | CREATE ROLE
            |--------------------------------------------------------------------------
            */

            $role = Role::create([

                'name' => $request->name,

                'guard_name' => 'web',
            ]);

            /*
            |--------------------------------------------------------------------------
            | ASSIGN PERMISSIONS
            |--------------------------------------------------------------------------
            */

            $role->syncPermissions(
                $request->permissions ?? []
            );

            return response()->json([

                'status' => true,

                'message' => 'Role created successfully',

                'data' => $role
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'status' => false,

                'message' => 'Create failed',

                'error' => $e->getMessage()

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        try {

            $role = Role::with('permissions')
                ->findOrFail($id);

            return response()->json([

                'status' => true,

                'data' => $role
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'status' => false,

                'message' => 'Role not found',

                'error' => $e->getMessage()

            ], 404);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $request->validate([

            'name' => 'required|unique:roles,name,' . $id,

            'permissions' => 'nullable|array',
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | FIND ROLE
            |--------------------------------------------------------------------------
            */

            $role = Role::findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | UPDATE ROLE
            |--------------------------------------------------------------------------
            */

            $role->update([

                'name' => $request->name,
            ]);

            /*
            |--------------------------------------------------------------------------
            | UPDATE PERMISSIONS
            |--------------------------------------------------------------------------
            */

            $role->syncPermissions(
                $request->permissions ?? []
            );

            return response()->json([

                'status' => true,

                'message' => 'Role updated successfully',

                'data' => $role
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'status' => false,

                'message' => 'Update failed',

                'error' => $e->getMessage()

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | FIND ROLE
            |--------------------------------------------------------------------------
            */

            $role = Role::findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | PREVENT ADMIN DELETE
            |--------------------------------------------------------------------------
            */

            if ($role->name === 'Admin') {

                return response()->json([

                    'status' => false,

                    'message' => 'Admin role cannot be deleted'

                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | DELETE ROLE
            |--------------------------------------------------------------------------
            */

            $role->delete();

            return response()->json([

                'status' => true,

                'message' => 'Role deleted successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'status' => false,

                'message' => 'Delete failed',

                'error' => $e->getMessage()

            ], 500);
        }
    }
}