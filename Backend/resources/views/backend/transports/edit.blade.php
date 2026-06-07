@extends('layouts.app')

@section('title', 'Edit Transport - Admin TravelViet')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Transport: {{ $transport->name }}</h5>

                <a href="{{ route('admin.transports.index') }}" class="btn btn-secondary">
                    Back to List
                </a>
            </div>

            <div class="card-body">
                <form method="POST" 
                      action="{{ route('admin.transports.update', $transport) }}" 
                      id="transportForm"
                      enctype="multipart/form-data"> {{-- Thêm enctype --}}
                    @csrf
                    @method('PUT') {{-- Sửa thành PUT --}}

                    {{-- Name + Slug --}}
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label">
                                Vehicle Name <span class="text-danger">*</span>
                            </label>

                            <div class="input-group input-group-merge">
                                <span class="input-group-text">
                                    <i class="bx bx-car"></i>
                                </span>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $transport->name) }}"
                                    placeholder="Toyota Fortuner X"
                                    required>
                            </div>

                            @error('name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Slug</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">
                                    <i class="bx bx-link"></i>
                                </span>
                                <input
                                    type="text"
                                    name="slug"
                                    id="slug"
                                    class="form-control"
                                    value="{{ old('slug', $transport->slug) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Destination + Transmission --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                Destination <span class="text-danger">*</span>
                            </label>

                            <select name="id_destination" class="form-select @error('id_destination') is-invalid @enderror" required>
                                <option value="">-- Select Destination --</option>
                                @foreach($destinations as $destination)
                                    <option
                                        value="{{ $destination->id }}"
                                        {{ old('id_destination', $transport->id_destination) == $destination->id ? 'selected' : '' }}>
                                        {{ $destination->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_destination')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Transmission</label>
                            <select name="transmission" class="form-select">
                                <option value="Automatic" {{ old('transmission', $transport->transmission) == 'Automatic' ? 'selected' : '' }}>
                                    Automatic
                                </option>
                                <option value="Manual" {{ old('transmission', $transport->transmission) == 'Manual' ? 'selected' : '' }}>
                                    Manual
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Mileage / Seats / Trips --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Mileage</label>
                            <input
                                type="text"
                                name="mileage"
                                class="form-control"
                                value="{{ old('mileage', $transport->mileage) }}"
                                placeholder="6542 miles">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Seats</label>
                            <input
                                type="number"
                                name="seats"
                                class="form-control"
                                value="{{ old('seats', $transport->seats) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Trips</label>
                            <input
                                type="number"
                                name="trips"
                                class="form-control"
                                value="{{ old('trips', $transport->trips) }}">
                        </div>
                    </div>

                    {{-- Rating / Review / Price --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Rating</label>
                            <input
                                type="number"
                                step="0.1"
                                min="0"
                                max="5"
                                name="rating"
                                class="form-control"
                                value="{{ old('rating', $transport->rating) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Reviews</label>
                            <input
                                type="number"
                                name="review"
                                class="form-control"
                                value="{{ old('review', $transport->review) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Price (VNĐ)</label>
                            <input
                                type="number"
                                name="price"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $transport->price) }}"
                                required>
                            
                            @error('price')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Image - Sửa thành file upload --}}
                    <div class="mb-4">
                        <label class="form-label">Vehicle Image</label>
                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/*">
                        
                        <small class="text-muted">Allowed formats: JPEG, PNG, JPG, GIF, WEBP. Max size: 2MB</small>

                        @error('image')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                        
                        {{-- Hiển thị ảnh hiện tại --}}
                        @if($transport->image)
                            <div class="mt-2" id="currentImage">
                                <p class="mb-1">Current Image:</p>
                                <img src="{{ $transport->image }}" alt="Current image" class="rounded" width="150" height="100" style="object-fit: cover;">
                            </div>
                        @endif

                        {{-- Preview ảnh mới --}}
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <p class="mb-1">New Image Preview:</p>
                            <img id="previewImg" src="#" alt="Preview" class="rounded" width="150" height="100" style="object-fit: cover;">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="status"
                                value="1"
                                {{ old('status', $transport->status) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary me-2">
                            Update Transport
                        </button>
                        <a href="{{ route('admin.transports.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
function stringToSlug(str) {
    str = str.toLowerCase();
    str = str.normalize('NFD').replace(/[\u0300-\u036f]/g,'');
    str = str.replace(/[đĐ]/g,'d');
    str = str.replace(/([^0-9a-z-\s])/g,'');
    str = str.replace(/\s+/g,'-');
    str = str.replace(/^-+|-+$/g,'');
    return str;
}

const nameInput = document.getElementById('name');
const slugInput = document.getElementById('slug');

// Đặt mặc định là true cho edit view để tránh tự động đè slug cũ
let manualSlug = true;

nameInput?.addEventListener('input', function(){
    if(!manualSlug) {
        slugInput.value = stringToSlug(this.value);
    }
});

slugInput?.addEventListener('input', function(){
    manualSlug = true;
});

// Nếu người dùng xóa hết slug thủ công, cho phép tự động generate lại dựa theo tên mới
nameInput?.addEventListener('click', function() {
    if(slugInput.value === '') {
        manualSlug = false;
    }
});

// Preview ảnh trước khi upload
document.getElementById('image')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            previewImg.src = event.target.result;
            preview.style.display = 'block';
            
            // Ẩn ảnh cũ nếu có
            const currentImage = document.getElementById('currentImage');
            if (currentImage) {
                currentImage.style.display = 'none';
            }
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection