<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function googleAuth(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        try {
            $response = Http::timeout(10)
                ->retry(3, 100)
                ->get('https://www.googleapis.com/oauth2/v3/userinfo', [
                    'access_token' => $request->token,
                ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Google token không hợp lệ hoặc đã hết hạn',
                ], 401);
            }

            $payload = $response->json();

            $email = $payload['email'] ?? null;
            $name = $payload['name'] ?? null;
            $avatar = $payload['picture'] ?? null;

            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể lấy email từ Google. Vui lòng kiểm tra quyền truy cập.'
                ], 401);
            }

            $isNewUser = false;
            $user = User::where('email', $email)->first();

            if (!$user) {
                $baseUsername = explode('@', $email)[0];
                $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', $baseUsername);
                $username = $baseUsername;
                $counter = 1;
                
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                $user = User::create([
                    'full_name' => $name ?? $username,
                    'email' => $email,
                    'username' => $username,
                    'password' => Hash::make(Str::random(32)),
                    'avatar' => $avatar,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);

                $isNewUser = true;
            } else {
                if (!$user->avatar && $avatar) {
                    $user->avatar = $avatar;
                    $user->save();
                }
            }

            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ hỗ trợ.'
                ], 403);
            }

            $user->tokens()->delete();

            $accessToken = $user->createToken('google_auth_' . now()->timestamp)->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => $isNewUser ? 'Đăng ký Google thành công' : 'Đăng nhập Google thành công',
                'access_token' => $accessToken,
                'user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                    'is_active' => $user->is_active,
                ]
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể kết nối đến Google. Vui lòng thử lại sau.'
            ], 504);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}