<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\DTO\UserDTO; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class staffProfileController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }


    /* ================= PROFILE ================= */

public function profile()
{
    $user = auth()->user();
    return view('staff.profile', compact('user'));
}

/* ================= UPDATE PROFILE ================= */


public function profileUpdate(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048' 
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