<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\DTO\UserDTO; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /* ================= USERS ================= */

    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    /* ================= STORE ================= */

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'nullable|min:6',
            'role'     => 'required|in:admin,staff,customer'
        ]);

        try {

            // ✅ DTO HANDLE ALL LOGIC
            $dto = UserDTO::fromAdmin($request);

            $this->service->createUser($dto);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Something went wrong!');
        }
    }

    /* ================= LIST ================= */

    public function list()
    {
        return response()->json([
            'data' => $this->service->getAllUsers()
        ]);
    }

    /* ================= EDIT ================= */

    public function edit($id)
    {
        $user = $this->service->getUserById($id);

        if (!$user) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'User not found');
        }

        return view('admin.users.create', compact('user'));
    }

    /* ================= UPDATE ================= */

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role'     => 'required|in:admin,staff,customer'
        ]);

        try {

            // ✅ DTO UPDATE
            $dto = UserDTO::fromUpdate($request);

            $this->service->updateUser($id, $dto);

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User updated successfully');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Update failed!');
        }
    }

    /* ================= FORCE RESET ================= */

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

    /* ================= CUSTOMERS ================= */

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

    /* ================= CUSTOMER PROFILE ================= */

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

    /* ================= CUSTOMER ORDERS ================= */

    public function customerOrders($id)
    {
        return response()->json([
            'data' => $this->service->getCustomerOrders($id)
        ]);
    }

    /* ================= DELETE ================= */

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


    /* ================= PROFILE ================= */

public function profile()
{
    $user = auth()->user();
    return view('admin.users.profile', compact('user'));
}

/* ================= UPDATE PROFILE ================= */


public function profileUpdate(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048' // ✅ FIXED
    ]);

    try {

        $data = [
            'name'  => $request->name,
            'email' => $request->email
        ];

        /* ================= IMAGE UPLOAD ================= */

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            // ✅ Generate safe filename
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // ✅ Store in storage/app/public/uploads
            $path = $file->storeAs('uploads', $filename, 'public');

            // ✅ Delete old image (if exists)
            if ($user->image && Storage::disk('public')->exists('uploads/'.$user->image)) {
                Storage::disk('public')->delete('uploads/'.$user->image);
            }

            // Save only filename
            $data['image'] = $filename;
        }

        /* ================= UPDATE USER ================= */

        $user->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully',
            'image_url' => isset($data['image'])
                ? asset('storage/uploads/'.$data['image'])
                : null
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status'  => false,
            'message' => 'Update failed',
            'error'   => $e->getMessage() // 🔥 helpful for debugging
        ], 500);
    }
}

/* ================= CHANGE PASSWORD ================= */

public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password'     => 'required|min:6|confirmed',
    ]);

    $user = auth()->user();

    try {

        if (!\Hash::check($request->current_password, $user->password)) {

            return response()->json([
                'status'  => false,
                'message' => 'Current password incorrect'
            ], 400);
        }

        // ✅ UPDATE PASSWORD VIA DTO
        $dto = new \App\DTO\UserDTO(
            $user->name,
            $user->email,
            $request->new_password,
            $user->role
        );

        $this->service->updateUser($user->id, $dto);

        return response()->json([
            'status'  => true,
            'message' => 'Password updated successfully'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status'  => false,
            'message' => 'Password update failed'
        ], 500);
    }
}
}