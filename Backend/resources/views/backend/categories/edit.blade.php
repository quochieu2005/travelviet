@extends('layouts.app')

@section('title', 'Edit Categories - Admin TravelViet')

@section('content')

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xxl">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Edit Categories</h5>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">Back to
                                List</a>
                        </div>

                        @include('components._message')

                        <div class="card-body">
                            <form action="{{ route('admin.categories.update', ['categories' => $categories->slug]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!-- Name -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="name">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" placeholder="Enter categories name"
                                            value="{{ old('name', $categories->name) }}" />
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Slug -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="slug">Slug</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            placeholder="auto-generated from name"
                                            value="{{ old('slug', $categories->slug) }}" />
                                        <div class="form-text">Unique URL-friendly string. Leave empty to auto-generate.
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="description">Description</label>
                                    <div class="col-sm-10">
                                        <textarea id="description" class="form-control" name="description" placeholder="Enter categories description"
                                            rows="4">{{ old('description', $categories->description) }}</textarea>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="status">Status</label>
                                    <div class="col-sm-10">
                                        <select id="status" class="form-select" name="status">
                                            <option value="active"
                                                {{ old('status', $categories->status) == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ old('status', $categories->status) == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Image với preview -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="image">Image</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" id="image" name="image"
                                            accept="image/*" />
                                        <div class="form-text mt-2">Upload categories image (JPG, PNG, JPEG - max 2MB)
                                        </div>

                                        <!-- Hiển thị ảnh song song: cũ bên trái, mới bên phải -->
                                        <div class="row mt-3" id="imagePreviewContainer">
                                            <!-- Ảnh cũ bên trái -->
                                            <div class="col-md-6">
                                                <div id="oldImageContainer" class="mb-2">
                                                    <p class="mb-1"><strong>Current Image:</strong></p>
                                                    @if (!empty($categories->image))
                                                        <img id="oldImage" src="{{ trim($categories->image) }}"
                                                            alt="Current Image" class="img-fluid rounded border p-1"
                                                            style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                                        <div class="mt-2">
                                                            <button type="button" id="removeOldImage"
                                                                class="btn btn-sm btn-danger">
                                                                Remove Image
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-warning">
                                                            No image found
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Preview ảnh mới bên phải -->
                                            <div class="col-md-6">
                                                <div id="newImagePreviewContainer" style="display: none;">
                                                    <p class="mb-1"><strong>New Image Preview:</strong></p>
                                                    <img id="newImagePreview" src="#" alt="New Image Preview"
                                                        class="img-fluid rounded border p-1"
                                                        style="max-width: 100%; max-height: 200px; object-fit: cover;" />
                                                    <div class="mt-2">
                                                        <button type="button" id="cancelNewImage"
                                                            class="btn btn-sm btn-warning">
                                                            Cancel New Image
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Update categories</button>
                                        <a href="{{ route('admin.categories.index') }}"
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
        function stringToSlug(str) {
            str = str.toLowerCase();
            str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            str = str.replace(/[đĐ]/g, 'd');
            str = str.replace(/([^0-9a-z-\s])/g, '');
            str = str.replace(/\s+/g, '-');
            str = str.replace(/^-+|-+$/g, '');
            return str;
        }

        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const imageInput = document.getElementById('image');
        const newImagePreviewContainer = document.getElementById('newImagePreviewContainer');
        const newImagePreview = document.getElementById('newImagePreview');
        const cancelNewImageBtn = document.getElementById('cancelNewImage');
        const oldImageContainer = document.getElementById('oldImageContainer');

        let isSlugManuallyEdited = false;

        nameInput.addEventListener('input', function() {
            if (!isSlugManuallyEdited) {
                slugInput.value = stringToSlug(this.value);
            }
        });

        slugInput.addEventListener('input', function() {
            isSlugManuallyEdited = true;
        });

        slugInput.addEventListener('blur', function() {
            if (this.value === '' && nameInput.value !== '') {
                slugInput.value = stringToSlug(nameInput.value);
                isSlugManuallyEdited = false;
            }
        });

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, JPEG, PNG, GIF)');
                    imageInput.value = '';
                    newImagePreviewContainer.style.display = 'none';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('File size should not exceed 2MB');
                    imageInput.value = '';
                    newImagePreviewContainer.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    newImagePreview.src = e.target.result;
                    newImagePreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                newImagePreviewContainer.style.display = 'none';
                newImagePreview.src = '#';
            }
        });

        cancelNewImageBtn.addEventListener('click', function() {
            imageInput.value = ''; 
            newImagePreviewContainer.style.display = 'none';
            newImagePreview.src = '#';
        });
    </script>
@endsection
