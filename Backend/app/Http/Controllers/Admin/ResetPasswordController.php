<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        $email = request()->query('email');   

        return view('admin.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),

            function ($admin, $password) {

                $admin->forceFill([
                    'password' => bcrypt($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {

            return redirect()
                ->route('admin.login')
                ->with('success', 'Đổi mật khẩu thành công');
        }

        return back()->withErrors([
            'email' => [__($status)]
        ]);
    }
}
