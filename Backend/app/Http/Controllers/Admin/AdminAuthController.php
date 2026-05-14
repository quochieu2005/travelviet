<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        // $remember    = $request->boolean('remember');
        $remember = $request->input('remember_sync') === '1';

        // if (Auth::guard('admin')->attempt($credentials, $remember)) {

        //     dd([
        //         'remember' => $remember,
        //         'token' => Auth::guard('admin')->user()->remember_token,
        //         'admin' => Auth::guard('admin')->user()->email,
        //         'password' => Auth::guard('admin')->user()->password,
        //         'remember_input' => $request->input('remember'), // xem giá trị thô từ form
        //     ]);
        // }

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Đăng nhập thành công!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Đã đăng xuất thành công.');
    }
}
