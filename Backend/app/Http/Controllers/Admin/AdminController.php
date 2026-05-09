<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function profile()
    {
        $admins = auth()->guard('admin')->user();

        return view('admin.my-profile', compact('admins'));
    }

    public function updateProfile(Request $request)
    {
        $admins = auth()->guard('admin')->user();

        try {
            $validated = $request->validate([
                'username' => 'nullable|max:255',
                'full_name' => 'nullable|max:255',
                'slug'     => 'nullable|max:255|unique:admins,slug,' . $admins->id,
                'email'    => 'nullable|email|unique:admins,email,' . $admins->id,
                'phone'    => 'nullable|max:20',
                'role'     => 'nullable|in:super_admin,admin',
                'avatar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $admins->username   = $request->username;
            $admins->full_name  = $request->full_name;
            $admins->slug       = $request->slug;
            $admins->email      = $request->email;
            $admins->phone      = $request->phone;
            $admins->role       = $request->role;

            if ($request->hasFile('avatar')) {
                if ($admins->avatar_id) {
                    app(CloudinaryService::class)->delete($admins->avatar_id);
                }

                $avatar = $request->file('avatar');

                $publicId = 'travelviet/avatars/' . $admins->slug;

                $uploadResult = app(CloudinaryService::class)->upload(
                    $avatar->getRealPath(),
                    $publicId
                );

                $admins->avatar    = $uploadResult['url'] ?? null;
                $admins->avatar_id = $uploadResult['public_id'] ?? null;
            }

            $admins->save();

            return redirect()->route('admin.my.profile')
                ->with('success', 'Hồ sơ đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function reset_password()
    {
        return view('admin.reset-password');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required'     => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min'          => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed'    => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $admins = auth()->guard('admin')->user();

        if (!Hash::check($request->current_password, $admins->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không chính xác.'
            ]);
        }

        $admins->password = bcrypt($request->new_password);

        $admins->save();

        return back()->with('success', 'Mật khẩu đã được cập nhật thành công!');
    }
}
