@extends('layouts.app')

@section('title', 'Thêm gói giá mới')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="d-flex align-items-center mb-4 gap-2">
        <a href="{{ route('admin.pricing.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h4 class="fw-bold m-0">Thêm gói giá mới</h4>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.pricing.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            {{-- Cột trái --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header"><strong>Thông tin gói</strong></div>
                    <div class="card-body row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Tên gói <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" placeholder="Basic, Pro, Custom..." required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nút bấm <span class="text-danger">*</span></label>
                            <input type="text" name="button_text" class="form-control"
                                   value="{{ old('button_text', 'Try Now') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mô tả ngắn</label>
                            <input type="text" name="description" class="form-control"
                                   value="{{ old('description') }}" placeholder="Best for personal and basic needs">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Giá (để trống nếu là gói Custom)</label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control"
                                       value="{{ old('price') }}" placeholder="10000000">
                                <span class="input-group-text">đ</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ghi chú giá</label>
                            <input type="text" name="price_note" class="form-control"
                                   value="{{ old('price_note', 'One-time payment') }}"
                                   placeholder="One-time payment">
                        </div>
                    </div>
                </div>

                {{-- Tính năng được bật --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>Tính năng (✅ bật)</strong>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFeature('features')">
                            <i class="bx bx-plus"></i> Thêm
                        </button>
                    </div>
                    <div class="card-body" id="features-list">
                        <div class="input-group mb-2">
                            <input type="text" name="features[]" class="form-control" placeholder="VD: 20+ Partners">
                            <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tính năng bị tắt --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>Tính năng bị tắt (❌ disabled)</strong>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addFeature('disabled_features')">
                            <i class="bx bx-plus"></i> Thêm
                        </button>
                    </div>
                    <div class="card-body" id="disabled_features-list">
                        <div class="input-group mb-2">
                            <input type="text" name="disabled_features[]" class="form-control" placeholder="VD: Business Card Scanner">
                            <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột phải --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header"><strong>Cài đặt</strong></div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" name="order" class="form-control"
                                   value="{{ old('order', 0) }}" min="0">
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_popular"
                                   id="is_popular" value="1"
                                   {{ old('is_popular') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_popular">
                                Đánh dấu "Popular"
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-save me-1"></i> Lưu gói giá
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function addFeature(group) {
    const list = document.getElementById(group + '-list')
    const div = document.createElement('div')
    div.className = 'input-group mb-2'
    div.innerHTML = `
        <input type="text" name="${group}[]" class="form-control" placeholder="Nhập tính năng...">
        <button type="button" class="btn btn-outline-danger" onclick="removeFeature(this)">
            <i class="bx bx-x"></i>
        </button>`
    list.appendChild(div)
}

function removeFeature(btn) {
    btn.closest('.input-group').remove()
}
</script>
@endsection