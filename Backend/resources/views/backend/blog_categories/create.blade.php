@extends('layouts.app')

@section('title', 'Create Blog Category')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Create Blog Category</h5>
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>

        @include('components._message')

        <div class="card-body">
            <form action="{{ route('admin.blog-categories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Slug</label>
                    <input type="text" name="slug" id="slug"
                        class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}"
                        placeholder="Tự động tạo từ tên">
                    <small class="form-text text-muted">
                        <i class="icon-base bx bx-info-circle me-1"></i>
                        Để trống để tự động tạo từ tên, hoặc nhập slug tùy chỉnh (ví dụ: du-lich-viet-nam)
                    </small>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Status <span class="text-danger">*</span></label>

                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>

                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
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

        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        let isSlugManuallyEdited = false;

        // Khi nhập name, tự động tạo slug nếu chưa chỉnh sửa
        nameInput.addEventListener('input', function() {
            if (!isSlugManuallyEdited && this.value) {
                slugInput.value = stringToSlug(this.value);
            }
        });

        // Khi bắt đầu sửa slug, đánh dấu đã chỉnh sửa
        slugInput.addEventListener('input', function() {
            isSlugManuallyEdited = true;
        });

        // Khi rời khỏi ô slug
        slugInput.addEventListener('blur', function() {
            if (this.value === '' && nameInput.value !== '') {
                slugInput.value = stringToSlug(nameInput.value);
                isSlugManuallyEdited = false;
            }
        });
    </script>
@endsection
