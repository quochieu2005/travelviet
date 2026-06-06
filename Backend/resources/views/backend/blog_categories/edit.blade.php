@extends('layouts.app')

@section('title', 'Edit Blog Category')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Edit Blog Category: {{ $blogCategory->name }}</h5>
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>

        @include('components._message')

        <div class="card-body">
            <form action="{{ route('admin.blog-categories.update', $blogCategory) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $blogCategory->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Slug</label>
                    <input type="text" name="slug" id="slug"
                        class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug', $blogCategory->slug) }}" placeholder="Tự động tạo từ tên">
                    <small class="form-text text-muted">
                        <i class="icon-base bx bx-info-circle me-1"></i>
                        Để trống để tự động tạo từ tên, hoặc nhập slug tùy chỉnh
                    </small>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Status <span class="text-danger">*</span></label>

                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="1" {{ old('status', $blogCategory->status) == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ old('status', $blogCategory->status) == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
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
        let isSlugManuallyEdited = false;

        nameInput.addEventListener('input', function() {
            if (!isSlugManuallyEdited && this.value) {
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
    </script>
@endsection
