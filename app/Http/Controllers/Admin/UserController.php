<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\DTO\UserDTO;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('admin.users.index');
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $roles = Role::all();

        return view(
            'admin.users.create',
            compact('roles')
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

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|min:6',

            'role' => 'required|exists:roles,name',
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | DTO
            |--------------------------------------------------------------------------
            */

            $dto = UserDTO::fromAdmin($request);

            /*
            |--------------------------------------------------------------------------
            | CREATE USER
            |--------------------------------------------------------------------------
            */

            $user = $this->service->createUser($dto);

            /*
            |--------------------------------------------------------------------------
            | ASSIGN ROLE
            |--------------------------------------------------------------------------
            */

            $user->assignRole($request->role);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

public function list()
{
    try {

        /*
        |--------------------------------------------------------------------------
        | GET USERS
        |--------------------------------------------------------------------------
        */

        $users = $this->service
            ->getAllUsers();

        /*
        |--------------------------------------------------------------------------
        | TRANSFORM DATA
        |--------------------------------------------------------------------------
        */

        $data = $users->map(function ($user) {

            /*
            |--------------------------------------------------------------------------
            | ROLE
            |--------------------------------------------------------------------------
            */

            $role = $user
                ->getRoleNames()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | DEFAULT ROLE
            |--------------------------------------------------------------------------
            */

            if (!$role) {

                $role = 'No Role';
            }

            return [

                'id' => $user->id,

                'name' => $user->name,

                'email' => $user->email,

                /*
                |--------------------------------------------------------------------------
                | DYNAMIC ROLE
                |--------------------------------------------------------------------------
                */

                'role' => $role,

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                'status' => $user->status ?? 1,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | JSON RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'data' => $data
        ]);

    } catch (\Exception $e) {

        return response()->json([

            'status' => false,

            'message' => 'Failed to load users',

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
        $user = $this->service->getUserById($id);

        if (!$user) {

            return redirect()
                ->route('admin.users.index')
                ->with('error', 'User not found');
        }

        $roles = Role::all();

        return view(
            'admin.users.create',
            compact('user', 'roles')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,' . $id,

            'password' => 'nullable|min:6',

            'role' => 'required|exists:roles,name',
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | DTO
            |--------------------------------------------------------------------------
            */

            $dto = UserDTO::fromUpdate($request);

            /*
            |--------------------------------------------------------------------------
            | UPDATE USER
            |--------------------------------------------------------------------------
            */

            $this->service->updateUser($id, $dto);

            /*
            |--------------------------------------------------------------------------
            | UPDATE ROLE
            |--------------------------------------------------------------------------
            */

            $user = $this->service->getUserById($id);

            $user->syncRoles([$request->role]);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User updated successfully');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Update failed!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FORCE RESET
    |--------------------------------------------------------------------------
    */

    public function forceReset($id)
    {
        try {

            $this->service->forcePasswordReset($id);

            return response()->json([

                'status' => true,

                'message' => 'User must reset password on next login'
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'status' => false,

                'message' => 'Reset failed'
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMERS
    |--------------------------------------------------------------------------
    */

    public function customers()
    {
        return view('admin.users.customers');
    }

    public function customerList()
    {
        return response()->json([
            'data' => $this->service->getCustomerList()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER PROFILE
    |--------------------------------------------------------------------------
    */

    public function customerShow($id)
    {
        $data = $this->service->getCustomerProfile($id);

        if (!$data || !$data['user']) {

            return redirect()
                ->route('admin.users.customers')
                ->with('error', 'Customer not found');
        }

        return view('admin.users.show-customer', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER ORDERS
    |--------------------------------------------------------------------------
    */

    public function customerOrders($id)
    {
        return response()->json([
            'data' => $this->service->getCustomerOrders($id)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        try {

            $deleted = $this->service->deleteUser($id);

            if (!$deleted) {

                return response()->json([

                    'status' => false,

                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([

                'status' => true,

                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'status' => false,

                'message' => 'Delete failed'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {

            $deleted = $this->service->deleteUser($id);

            if (!$deleted) {

                return back()->with('error', 'User not found');
            }

            return back()->with('success', 'User deleted');

        } catch (\Exception $e) {

            return back()->with('error', 'Delete failed');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        $user = auth()->user();

        return view(
            'admin.users.profile',
            compact('user')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PROFILE
    |--------------------------------------------------------------------------
    */

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,' . $user->id,

            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        try {

            $data = [

                'name' => $request->name,

                'email' => $request->email,
            ];

            /*
            |--------------------------------------------------------------------------
            | IMAGE UPLOAD
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {

                $file = $request->file('image');

                $filename = Str::uuid() . '.'
                    . $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads',
                    $filename,
                    'public'
                );

                if (
                    $user->image &&
                    Storage::disk('public')->exists(
                        'uploads/' . $user->image
                    )
                ) {

                    Storage::disk('public')->delete(
                        'uploads/' . $user->image
                    );
                }

                $data['image'] = $filename;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE USER
            |--------------------------------------------------------------------------
            */

            $user->update($data);

            return response()->json([

                'status' => true,

                'message' => 'Profile updated successfully',

                'image_url' => isset($data['image'])
                    ? asset('storage/uploads/' . $data['image'])
                    : null
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
    | CHANGE PASSWORD
    |--------------------------------------------------------------------------
    */

    public function changePassword(Request $request)
    {
        $request->validate([

            'current_password' => 'required',

            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        try {

            if (
                !Hash::check(
                    $request->current_password,
                    $user->password
                )
            ) {

                return response()->json([

                    'status' => false,

                    'message' => 'Current password incorrect'

                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | DTO
            |--------------------------------------------------------------------------
            */

            $dto = new UserDTO(

                $user->name,

                $user->email,

                $request->new_password,

                $user->getRoleNames()->first()
            );

            /*
            |--------------------------------------------------------------------------
            | UPDATE PASSWORD
            |--------------------------------------------------------------------------
            */

            $this->service->updateUser($user->id, $dto);

            return response()->json([

                'status' => true,

                'message' => 'Password updated successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'status' => false,

                'message' => 'Password update failed'

            ], 500);
        }
    }
}