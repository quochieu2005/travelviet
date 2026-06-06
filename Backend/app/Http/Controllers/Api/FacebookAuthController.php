<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FacebookAuthController extends Controller
{
    public function facebookLogin(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string'
        ]);

        try {
            $response = Http::get('https://graph.facebook.com/me', [
                'fields' => 'id,name,email,picture.width(400).height(400)',
                'access_token' => $request->access_token
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facebook token không hợp lệ hoặc đã hết hạn'
                ], 401);
            }

            $facebookUser = $response->json();

            $email = $facebookUser['email'] ?? null;
            $facebookId = $facebookUser['id'];
            $name = $facebookUser['name'] ?? null;
            $avatar = $facebookUser['picture']['data']['url'] ?? null;

            // Tìm user theo username (facebook_id) hoặc email
            $user = User::where('username', $facebookId)
                ->orWhere('email', $email)
                ->first();

            $isNewUser = false;

            if (!$user) {
                $user = User::create([
                    'full_name'         => $name ?? 'Facebook User',
                    'email'             => $email ?? ($facebookId . '@facebook.com'),
                    'username'          => $facebookId,
                    'password'          => Hash::make(Str::random(32)),
                    'avatar'            => $avatar,
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]);

                $isNewUser = true;
            } else {
                // Cập nhật avatar nếu chưa có
                if (!$user->avatar && $avatar) {
                    $user->avatar = $avatar;
                    $user->save();
                }
            }

            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản của bạn đã bị khóa'
                ], 403);
            }

            $user->tokens()->delete();
            $accessToken = $user->createToken('facebook_auth_' . now()->timestamp)->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => $isNewUser ? 'Đăng ký Facebook thành công' : 'Đăng nhập Facebook thành công',
                'access_token' => $accessToken,
                'user' => [
                    'id'        => $user->id,
                    'full_name' => $user->full_name,
                    'email'     => $user->email,
                    'username'  => $user->username,
                    'avatar'    => $user->avatar,
                    'is_active' => $user->is_active,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}