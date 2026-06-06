@extends('layouts.app')

@section('title', 'Edit Hotels')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Edit Hotel: {{ $hotel->name }}</h4>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">← Back</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.hotels.update', $hotel->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Hotel Name -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hotel Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $hotel->name ?? '') }}" required>
                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                value="{{ old('slug', $hotel->slug ?? '') }}" placeholder="auto-generated from name">
                            <div class="form-text">Unique URL-friendly string. Leave empty to auto-generate.</div>
                            @error('slug')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Destination <span class="text-danger">*</span></label>
                            <select name="destination_id" class="form-select" required>
                                <option value="">-- Select Destination --</option>
                                @foreach ($destinations as $dest)
                                    <option value="{{ $dest->id }}"
                                        {{ old('destination_id', $hotel->destination_id ?? '') == $dest->id ? 'selected' : '' }}>
                                        {{ $dest->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('destination_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Price (₫) <span class="text-danger">*</span></label>
                            <input type="text" name="price" id="price" class="form-control"
                                value="{{ old('price', number_format($hotel->price ?? 0, 0, ',', '.')) }}" required>
                            @error('price')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Rating <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" min="0" max="5" name="rating"
                                class="form-control" value="{{ old('rating', $hotel->rating ?? '') }}" required>
                            @error('rating')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Reviews <span class="text-danger">*</span></label>
                            <input type="number" name="reviews" class="form-control"
                                value="{{ old('reviews', $hotel->reviews ?? '') }}" required>
                            @error('reviews')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Short Description</label>
                            <input type="text" name="short_description" class="form-control"
                                value="{{ old('short_description', $hotel->short_description ?? '') }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $hotel->description ?? '') }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Thumbnail</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*"
                                onchange="previewImage(this)">
                            <div id="imagePreview" class="mt-2"></div>
                            @if (!empty($hotel->thumbnail))
                                <div id="oldImage">
                                    <label class="form-label mt-2">Current Image:</label>
                                    <img src="{{ $hotel->thumbnail }}" width="150" class="rounded d-block">
                                </div>
                            @endif
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="status" value="0">

                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1"
                                {{ old('status', $hotel->status ?? 1) ? 'checked' : '' }}>

                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>
                    </div>

                    {{-- FACILITIES --}}
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">Facilities</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addFacility">+ Add
                                Facility</button>
                        </div>
                        <div id="facilityList">
                            @if (!empty($hotel->facilities))
                                @foreach ($hotel->facilities as $i => $facility)
                                    <div class="d-flex gap-2 mb-2 facility-row">
                                        <select name="facilities[{{ $i }}][icon]" class="form-select">
                                            <option value="">-- Select Icon --</option>
                                            @foreach ($icons as $iconClass => $iconName)
                                                <option value="{{ $iconClass }}"
                                                    {{ ($facility['icon'] ?? '') === $iconClass ? 'selected' : '' }}>
                                                    {{ $iconName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-danger btn-sm remove-facility">✕</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-warning px-4">Update Hotel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Format price function
        function formatPrice(value) {
            let number = value.replace(/[^\d]/g, '');
            return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Price input handler
        const priceInput = document.getElementById('price');
        if (priceInput) {
            priceInput.addEventListener('input', function() {
                let formatted = formatPrice(this.value);
                this.value = formatted;
            });
        }

        // Preview image function
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const oldImage = document.getElementById('oldImage');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" width="150" class="rounded mt-2">`;
                    if (oldImage) oldImage.style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
                if (oldImage) oldImage.style.display = 'block';
            }
        }

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

        let isSlugManuallyEdited = false;
        const originalSlug = slugInput.value;

        // Khi nhập name, nếu chưa sửa slug hoặc slug giống với giá trị cũ thì tự động cập nhật
        if (nameInput) {
            nameInput.addEventListener('input', function() {
                if (!isSlugManuallyEdited || slugInput.value === originalSlug) {
                    slugInput.value = stringToSlug(this.value);
                    isSlugManuallyEdited = false;
                }
            });
        }

        // Khi người dùng bắt đầu sửa slug
        if (slugInput) {
            slugInput.addEventListener('input', function() {
                isSlugManuallyEdited = true;
            });

            // Khi blur khỏi slug, nếu slug trống thì tự động sinh lại từ name
            slugInput.addEventListener('blur', function() {
                if (this.value === '' && nameInput.value !== '') {
                    slugInput.value = stringToSlug(nameInput.value);
                    isSlugManuallyEdited = false;
                }
            });
        }

        // Facilities
        let facilityCount = {{ !empty($hotel->facilities) ? count($hotel->facilities) : 0 }};
        const icons = @json($icons);

        const addFacilityBtn = document.getElementById('addFacility');
        if (addFacilityBtn) {
            addFacilityBtn.addEventListener('click', function() {
                let options = '<option value="">-- Select Icon --</option>';
                for (const [cls, name] of Object.entries(icons)) {
                    options += `<option value="${cls}">${name}</option>`;
                }
                const row = `
            <div class="d-flex gap-2 mb-2 facility-row">
                <select name="facilities[${facilityCount}][icon]" class="form-select">${options}</select>
                <button type="button" class="btn btn-danger btn-sm remove-facility">✕</button>
            </div>`;
                document.getElementById('facilityList').insertAdjacentHTML('beforeend', row);
                facilityCount++;
                bindRemove();
            });
        }

        function bindRemove() {
            document.querySelectorAll('.remove-facility').forEach(btn => {
                btn.onclick = () => btn.closest('.facility-row').remove();
            });
        }
        bindRemove();
    </script>
@endsection
