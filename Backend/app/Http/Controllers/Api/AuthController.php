<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'password'  => 'required|min:6'
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
        ]);

        // Tạo token Sanctum
        $token = $user->createToken('web')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'access_token'   => $token,
            'user'    => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }

        // Nếu muốn mỗi lần login chỉ có 1 token
        $user->tokens()->delete();

        // Tạo token mới
        $token = $user->createToken('web')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'access_token'   => $token,
            'user'    => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'username'    => 'nullable|string|max:50|unique:users,username,' . $user->id,
            'full_name'   => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:500',
            'nationality' => 'nullable|string|max:100',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'username',
            'full_name',
            'phone',
            'address',
            'nationality'
        ]);

        if ($request->hasFile('avatar')) {

            $image = $request->file('avatar');

            // Tạo slug từ full_name
            $slug = Str::slug(
                $request->full_name ?? $user->full_name ?? 'user'
            );

            $publicId = 'travelviet/User/' . $slug;

            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );

            $data['avatar'] = $uploadResult['url'] ?? null;
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật hồ sơ thành công',
            'user'    => $user->fresh()
        ]);
    }
}
