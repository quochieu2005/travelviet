@extends('layouts.app')

@section('title', 'Create Destination - Admin TravelViet')

@section('content')

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xxl">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Create Destination</h5>
                            <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary btn-sm">Back to
                                List</a>
                        </div>

                        @include('components._message')

                        <div class="card-body">
                            <form action="{{ route('admin.destinations.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <!-- Name -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="name">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter destination name" />
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="slug">Slug</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            placeholder="auto-generated from name" />
                                        <div class="form-text">Unique URL-friendly string. Leave empty to auto-generate.
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="description">Description</label>
                                    <div class="col-sm-10">
                                        <textarea id="description" class="form-control" name="description" placeholder="Enter destination description"
                                            rows="4"></textarea>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="status">Status</label>
                                    <div class="col-sm-10">
                                        <select id="status" class="form-select" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Image với preview -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="image">Image</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" id="image" name="image"
                                            accept="image/*" />
                                        <div class="form-text mt-2">Upload destination image (JPG, PNG, JPEG - max 2MB)
                                        </div>
                                        <!-- Image Preview -->
                                        <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                                            <img id="imagePreview" src="#" alt="Image Preview"
                                                style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd; padding: 5px;" />
                                            <button type="button" id="removeImage"
                                                class="btn btn-sm btn-danger mt-2">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Create Destination</button>
                                        <a href="{{ route('admin.destinations.index') }}"
                                            class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
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
        // Hàm chuyển đổi string thành slug
        function stringToSlug(str) {
            str = str.toLowerCase();
            str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            str = str.replace(/[đĐ]/g, 'd');
            str = str.replace(/([^0-9a-z-\s])/g, '');
            str = str.replace(/\s+/g, '-');
            str = str.replace(/^-+|-+$/g, '');
            return str;
        }

        // Lấy các element
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const imageInput = document.getElementById('image');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const removeImageBtn = document.getElementById('removeImage');

        // Biến để theo dõi user đã tự sửa slug chưa
        let isSlugManuallyEdited = false;

        // Khi name thay đổi, tự động cập nhật slug (nếu chưa được chỉnh sửa thủ công)
        nameInput.addEventListener('input', function() {
            if (!isSlugManuallyEdited) {
                slugInput.value = stringToSlug(this.value);
            }
        });

        // Khi user bắt đầu chỉnh sửa slug, đánh dấu là đã chỉnh sửa thủ công
        slugInput.addEventListener('input', function() {
            isSlugManuallyEdited = true;
        });

        // Nếu slug trống và name có giá trị, cho phép auto-generate lại (tùy chọn)
        slugInput.addEventListener('blur', function() {
            if (this.value === '' && nameInput.value !== '') {
                slugInput.value = stringToSlug(nameInput.value);
                isSlugManuallyEdited = false;
            }
        });

        // Preview ảnh khi chọn file
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Kiểm tra định dạng file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, JPEG, PNG, GIF)');
                    imageInput.value = '';
                    imagePreviewContainer.style.display = 'none';
                    return;
                }

                // Kiểm tra kích thước file (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size should not exceed 2MB');
                    imageInput.value = '';
                    imagePreviewContainer.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreviewContainer.style.display = 'none';
                imagePreview.src = '#';
            }
        });

        // Xóa ảnh đã chọn
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreviewContainer.style.display = 'none';
            imagePreview.src = '#';
        });
    </script>
@endsection
