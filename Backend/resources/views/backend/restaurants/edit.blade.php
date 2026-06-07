@extends('layouts.app')

@section('title', 'Edit Restaurant - Admin TravelViet')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xxl">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Edit Restaurant</h5>
                            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-secondary btn-sm">
                                Back to List
                            </a>
                        </div>

                        @include('components._message')

                        <div class="card-body">
                            {{-- Thay đổi route sang .update và thêm biến $restaurant->id --}}
                            <form action="{{ route('admin.restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                {{-- Bắt buộc phải có @method('PUT') cho route Update của Resource --}}
                                @method('PUT')
                                
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="title">Title <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                            value="{{ old('title', $restaurant->title) }}" placeholder="Enter restaurant name" required />
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="slug">Slug</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug"
                                            value="{{ old('slug', $restaurant->slug) }}" placeholder="auto-generated from title" />
                                        <div class="form-text">Unique URL-friendly string. Leave empty to auto-generate.</div>
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="location">Location <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location"
                                            value="{{ old('location', $restaurant->location) }}" placeholder="e.g., 123 Nguyen Trai, District 1, HCMC" required />
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="price">Price (₫) <span class="text-danger">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                                            value="{{ old('price', $restaurant->price) }}" placeholder="Current price" min="0" required />
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <label class="col-sm-2 col-form-label text-sm-end" for="oldprice">Old Price (₫)</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control @error('oldprice') is-invalid @enderror" id="oldprice" name="oldprice"
                                            value="{{ old('oldprice', $restaurant->oldprice) }}" placeholder="Original price (if discount)" min="0" />
                                        @error('oldprice')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="rating">Rating</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating"
                                            value="{{ old('rating', $restaurant->rating ?? 0) }}" placeholder="e.g., 4.5" step="0.1" min="0" max="5" />
                                        @error('rating')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <label class="col-sm-2 col-form-label text-sm-end" for="reviews">Reviews count</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control @error('reviews') is-invalid @enderror" id="reviews" name="reviews"
                                            value="{{ old('reviews', $restaurant->reviews ?? 0) }}" placeholder="e.g., 120" min="0" />
                                        @error('reviews')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="tag">Tag / Badge</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('tag') is-invalid @enderror" id="tag" name="tag"
                                            value="{{ old('tag', $restaurant->tag) }}" placeholder="e.g., Popular, New, 20% OFF" />
                                        @error('tag')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="image">Restaurant Image</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image"
                                            accept="image/*" />
                                        <div class="form-text mt-2">Upload a new image to replace the current one (JPG, JPEG, PNG, GIF, WEBP - max 2MB)</div>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        {{-- Khu vực hiển thị ảnh: mặc định lấy ảnh cũ từ db nếu có --}}
                                        <div id="imagePreviewContainer" class="mt-3" style="{{ !empty($restaurant->image) ? 'display: block;' : 'display: none;' }}">
                                            <img id="imagePreview" src="{{ !empty($restaurant->image) ? $restaurant->image : '#' }}" alt="Image Preview"
                                                style="max-width: 240px; max-height: 160px; border-radius: 8px; border: 1px solid #ddd; padding: 5px; object-fit: cover;" />
                                            <br>
                                            <button type="button" id="removeImage" class="btn btn-sm btn-danger mt-2">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-secondary">Cancel</a>
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
        // Hàm chuyển đổi dữ liệu tiếng Việt thành slug chuẩn
        function stringToSlug(str) {
            str = str.toLowerCase();
            str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            str = str.replace(/[đĐ]/g, 'd');
            str = str.replace(/([^0-9a-z-\s])/g, '');
            str = str.replace(/\s+/g, '-');
            str = str.replace(/^-+|-+$/g, '');
            return str;
        }

        // Khai báo các Elements
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const imageInput = document.getElementById('image');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const removeImageBtn = document.getElementById('removeImage');

        // Đối với Edit, lưu lại link ảnh mặc định ban đầu từ DB để phục hồi nếu cần
        const originalImageSrc = "{{ !empty($restaurant->image) ? $restaurant->image : '#' }}";

        // Mặc định trang edit đã có slug cũ nên ta coi như user đã từng edit thủ công để tránh auto ghi đè khi chưa sửa Title
        let isSlugManuallyEdited = true;

        // Auto-generate Slug theo Title
        titleInput.addEventListener('input', function() {
            if (!isSlugManuallyEdited || slugInput.value === '') {
                slugInput.value = stringToSlug(this.value);
            }
        });

        slugInput.addEventListener('input', function() {
            isSlugManuallyEdited = true;
        });

        slugInput.addEventListener('blur', function() {
            if (this.value === '' && titleInput.value !== '') {
                slugInput.value = stringToSlug(titleInput.value);
                isSlugManuallyEdited = false;
            }
        });

        // Xử lý Preview hình ảnh tải lên Front-end
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPG, JPEG, PNG, GIF, WEBP)');
                    resetToOriginalImage();
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('File size should not exceed 2MB');
                    resetToOriginalImage();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                resetToOriginalImage();
            }
        });

        // Hàm khôi phục lại ảnh cũ ban đầu từ DB hoặc ẩn hẳn đi nếu chưa từng có ảnh
        function resetToOriginalImage() {
            imageInput.value = '';
            if (originalImageSrc !== '#') {
                imagePreview.src = originalImageSrc;
                imagePreviewContainer.style.display = 'block';
            } else {
                imagePreview.src = '#';
                imagePreviewContainer.style.display = 'none';
            }
        }

        removeImageBtn.addEventListener('click', resetToOriginalImage);
    </script>
@endsection