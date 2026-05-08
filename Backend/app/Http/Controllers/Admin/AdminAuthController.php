<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Validate the login form data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to log in the admin
        if (auth()->guard('admin')->attempt($credentials)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // If login fails, redirect back with an error message
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập được cung cấp không khớp với hồ sơ của chúng tôi!',
        ]);
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
