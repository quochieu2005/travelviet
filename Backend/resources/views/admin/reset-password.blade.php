@extends('layouts.app')
@section('title', 'Đặt lại mật khẩu - Admin TravelViet')

@section('content')

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">

                <div class="card px-sm-6 px-0">
                    <div class="card-body">

                        <h4 class="mb-1">Đặt lại mật khẩu 🔒</h4>
                        <p class="mb-6">
                            Vui lòng nhập mật khẩu mới để hoàn tất quá trình
                        </p>
                        @include('components._message')
                        
                        <form method="POST" action="{{ route('admin.reset.password.update') }}" class="mb-3">

                            @csrf

                            {{-- Mật khẩu hiện tại --}}
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="current_password">
                                    Mật khẩu hiện tại
                                </label>

                                <div class="input-group input-group-merge">
                                    <input type="password" id="current_password" name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        placeholder="············">

                                    <span class="input-group-text cursor-pointer">
                                        <i class="icon-base bx bx-hide"></i>
                                    </span>
                                </div>

                                @error('current_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Mật khẩu mới --}}
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="new_password">
                                    Mật khẩu mới
                                </label>

                                <div class="input-group input-group-merge">
                                    <input type="password" id="new_password" name="new_password"
                                        class="form-control @error('new_password') is-invalid @enderror"
                                        placeholder="············">

                                    <span class="input-group-text cursor-pointer">
                                        <i class="icon-base bx bx-hide"></i>
                                    </span>
                                </div>

                                @error('new_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Xác nhận mật khẩu --}}
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="new_password_confirmation">

                                    Xác nhận mật khẩu mới
                                </label>

                                <div class="input-group input-group-merge">
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                        class="form-control" placeholder="············">

                                    <span class="input-group-text cursor-pointer">
                                        <i class="icon-base bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">

                                    Cập nhật mật khẩu
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="bx bx-chevron-left scaleX-n1-rtl"></i>
                                    Quay lại trang chủ
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
