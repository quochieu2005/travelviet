@extends('layouts.app')
@section('title', 'My Profile - Admin TravelViet')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-align-top">
                        <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:void(0);">
                                    <i class="icon-base bx bx-user icon-sm me-1_5"></i> Account
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="">
                                    <i class="icon-base bx bx-bell icon-sm me-1_5"></i> Notifications
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="">
                                    <i class="icon-base bx bx-link-alt icon-sm me-1_5"></i> Connections
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card mb-6">
                        <div class="card-body">
                            <form action="{{ route('admin.my.profile.update') }}" method="POST"
                                enctype="multipart/form-data" id="profileForm">
                                @csrf

                                <!-- Avatar -->
                                <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                    <img src="{{ $admins->avatar ? $admins->avatar : asset('assets/img/avatars/1.png') }}"
                                        alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />

                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                                            <input type="file" id="upload" name="avatar" class="account-file-input"
                                                hidden accept="image/png, image/jpeg" />
                                        </label>

                                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-4"
                                            id="resetAvatar">
                                            <i class="icon-base bx bx-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>

                                        <div class="small text-muted">
                                            Allowed JPG, JPEG or PNG. Max size of 2MB
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Fields -->
                                <div class="pt-4">
                                    <div class="row g-6">
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">User Name</label>
                                            <input class="form-control" type="text" id="username" name="username"
                                                value="{{ old('username', $admins->username) }}" autofocus />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <input class="form-control" type="text" id="full_name" name="full_name"
                                                value="{{ old('full_name', $admins->full_name) }}" />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="slug" class="form-label">Slug</label>
                                            <input class="form-control" type="text" id="slug" name="slug"
                                                value="{{ old('slug', $admins->slug) }}" />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input class="form-control" type="email" id="email" name="email"
                                                value="{{ old('email', $admins->email) }}"
                                                placeholder="john.doe@example.com" />
                                        </div>

                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"></span>
                                                <input type="text" id="phone" name="phone" class="form-control"
                                                    value="{{ old('phone', $admins->phone) }}" placeholder="0123456789" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="role" class="form-label">Role <span
                                                    class="text-danger">*</span></label>
                                            <select id="role" name="role" class="form-select">
                                                <option value="">Chọn vai trò</option>
                                                <option value="super_admin"
                                                    {{ old('role', $admins->role) == 'super_admin' || $admins->role == 'super_admin' ? 'selected' : '' }}>
                                                    Super Admin
                                                </option>
                                                <option value="admin"
                                                    {{ old('role', $admins->role) == 'admin' || $admins->role == 'admin' ? 'selected' : '' }}>
                                                    Admin
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <button type="submit" class="btn btn-primary me-3">Save changes</button>
                                        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="card">
                        <h5 class="card-header">Delete Account</h5>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                                <p class="mb-0">Once you delete your account, there is no going back. Please be certain.
                                </p>
                            </div>
                            <form id="formAccountDeactivation" onsubmit="return false">
                                <div class="form-check my-8 ms-2">
                                    <input class="form-check-input" type="checkbox" name="accountActivation"
                                        id="accountActivation" />
                                    <label class="form-check-label" for="accountActivation">I confirm my account
                                        deactivation</label>
                                </div>
                                <button type="submit" class="btn btn-danger deactivate-account">Deactivate
                                    Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. XỬ LÝ AUTO-SLUG ---
            const fullNameInput = document.getElementById('full_name');
            const slugInput = document.getElementById('slug');

            if (fullNameInput && slugInput) {
                fullNameInput.addEventListener('input', function() {
                    let title = this.value;
                    let slug = title.toLowerCase();

                    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
                    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
                    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
                    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
                    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
                    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
                    slug = slug.replace(/đ/gi, 'd');

                    slug = slug.replace(
                        /\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi,
                        '');

                    slug = slug.replace(/ /gi, "-");

                    slug = slug.replace(/\-\-\-\-\-/gi, '-');
                    slug = slug.replace(/\-\-\-\-/gi, '-');
                    slug = slug.replace(/\-\-\-/gi, '-');
                    slug = slug.replace(/\-\-/gi, '-');

                    slug = '@' + slug + '@';
                    slug = slug.replace(/\@\-|\-\@|\@/gi, '');

                    slugInput.value = slug;
                });
            }

            // --- 2. XỬ LÝ PREVIEW ẢNH ---
            const uploadInput = document.getElementById('upload');
            const uploadedAvatar = document.getElementById('uploadedAvatar');
            const resetAvatar = document.getElementById('resetAvatar');

            if (uploadInput && uploadedAvatar) {
                const originalSrc = uploadedAvatar.src;

                uploadInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];

                        if (!file.type.startsWith('image/')) {
                            alert('Vui lòng chọn file hình ảnh (jpg, png, jpeg)!');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            uploadedAvatar.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });

                if (resetAvatar) {
                    resetAvatar.addEventListener('click', function() {
                        uploadInput.value = ''; 
                        uploadedAvatar.src = originalSrc; 
                    });
                }
            }
        });
    </script>
@endsection
