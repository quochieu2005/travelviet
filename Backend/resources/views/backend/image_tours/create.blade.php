@extends('layouts.app')

@section('title', 'Create Image Tour - Admin TravelViet')

@section('content')

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xxl">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Create Image Tour</h5>
                            <a href="{{ route('admin.image-tours.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                        </div>

                        @include('components._message')

                        <div class="card-body">
                            <form action="{{ route('admin.image-tours.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <!-- Select Tour -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="tour_id">Tour <span
                                            class="text-danger">*</span></label>

                                    <div class="col-sm-10">
                                        <select name="tour_id" id="tour_id" class="form-select" required>
                                            <option value="">-- Select Tour --</option>
                                            @foreach ($tours as $tour)
                                                <option value="{{ $tour->id }}"
                                                    {{ old('tour_id') == $tour->id ? 'selected' : '' }}>
                                                    {{ $tour->title }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('tour_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Multiple Images Upload -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="images">Images <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" id="images" name="images[]"
                                            accept="image/*" multiple required />
                                        <div class="form-text mt-2">
                                            <i class="fas fa-info-circle"></i> You can select multiple images (Max 10
                                            images, max 2MB each)
                                            <br>Supported formats: JPG, PNG, JPEG, GIF, SVG, WEBP
                                        </div>

                                        <!-- Images Preview & Sort Order Container -->
                                        <div id="imagesPreviewContainer" class="mt-3" style="display: none;">
                                            <label class="fw-bold">Image Preview & Sort Order:</label>
                                            <div id="sortOrdersList" class="mt-2"></div>
                                            <small class="text-muted">
                                                <i class="fas fa-arrows-alt"></i> Thứ tự sắp xếp thấp hơn = xuất hiện đầu
                                                tiên
                                                <br><i class="fas fa-mouse-pointer"></i> Bạn có thể tự điều chỉnh thứ tự sắp
                                                xếp cho từng hình ảnh.
                                            </small>
                                        </div>

                                        @error('images.*')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @error('images')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="type">Type</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="type" name="type"
                                            value="{{ old('type', 'tour') }}" placeholder="tour" />
                                        <div class="form-text">Optional: Specify image type (e.g., tour, banner, thumbnail)
                                        </div>
                                        @error('type')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Auto Sort Order Info -->
                                <div class="row mb-6">
                                    <div class="col-sm-10 offset-sm-2">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Sắp xếp tự động:</strong> Nếu bạn không thiết lập thứ tự sắp xếp thủ
                                            công, hình ảnh sẽ được sắp xếp tự động (hình ảnh mới nhất sẽ có số thứ tự cao
                                            nhất). Bạn có thể sắp xếp lại hình ảnh sau này bằng cách chỉnh sửa hoặc sử dụng
                                            thao tác kéo thả.

                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                                            <i class="fas fa-upload"></i> Upload Images
                                        </button>
                                        <a href="{{ route('admin.image-tours.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
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
        let imageItems = [];

        document.getElementById('images').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('imagesPreviewContainer');
            const sortOrdersList = document.getElementById('sortOrdersList');

            const files = Array.from(event.target.files);

            imageItems = [];

            if (files.length > 0 && files.length <= 10) {
                previewContainer.style.display = 'block';
                sortOrdersList.innerHTML = '';

                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        const imageId = 'img_' + Date.now() + '_' + index;

                        imageItems.push({
                            id: imageId,
                            file: file,
                            sortOrder: index + 1,
                            originalIndex: index
                        });

                        const previewWrapper = document.createElement('div');
                        previewWrapper.className = 'card mb-3';
                        previewWrapper.style.border = '1px solid #ddd';
                        previewWrapper.style.borderRadius = '8px';
                        previewWrapper.style.overflow = 'hidden';
                        previewWrapper.setAttribute('data-image-id', imageId);

                        previewWrapper.innerHTML = `
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img class="preview-img" style="width: 100%; height: 80px; object-fit: cover; border-radius: 5px;">
                                </div>
                                <div class="col-md-4">
                                    <strong>${file.name.substring(0, 40)}${file.name.length > 40 ? '...' : ''}</strong>
                                    <br>
                                    <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label mb-0">Thứ tự sắp xếp:</label>
                                    <input type="number" class="form-control sort-order-input"
                                           style="width: 100px;" value="${index + 1}" min="1" step="1">
                                    <small class="text-muted">Số nhỏ = Hiển thị trước</small>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-danger btn-sm remove-image">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                        reader.onload = function(e) {
                            previewWrapper.querySelector('.preview-img').src = e.target.result;
                        };

                        reader.readAsDataURL(file);

                        // ★ FIX 1: Sau khi xóa khỏi imageItems, rebuild lại file input ngay lập tức
                        const removeBtn = previewWrapper.querySelector('.remove-image');
                        removeBtn.addEventListener('click', function() {
                            const idToRemove = previewWrapper.getAttribute('data-image-id');
                            imageItems = imageItems.filter(item => item.id !== idToRemove);
                            previewWrapper.remove();

                            // Rebuild file input để đồng bộ với imageItems hiện tại
                            rebuildFileInput();

                            updateMaxValues();
                            toggleReorderButton();

                            if (imageItems.length === 0) {
                                previewContainer.style.display = 'none';
                            }
                        });

                        const sortInput = previewWrapper.querySelector('.sort-order-input');
                        sortInput.addEventListener('change', function() {
                            let newOrder = parseInt(this.value);
                            if (isNaN(newOrder) || newOrder < 1) newOrder = 1;

                            const itemIndex = imageItems.findIndex(item => item.id === imageId);
                            if (itemIndex !== -1) {
                                imageItems[itemIndex].sortOrder = newOrder;
                            }

                            toggleReorderButton();
                        });

                        sortOrdersList.appendChild(previewWrapper);
                    }
                });

                addReorderButton();

            } else if (files.length > 10) {
                alert('Bạn chỉ có thể upload tối đa 10 ảnh cùng lúc!');
                event.target.value = '';
                previewContainer.style.display = 'none';
                imageItems = [];
            }
        });

        function updateMaxValues() {
            const totalImages = imageItems.length;
            document.querySelectorAll('.sort-order-input').forEach(input => {
                input.max = totalImages;
            });
        }

        function toggleReorderButton() {
            let reorderBtn = document.getElementById('applyReorderBtn');
            if (reorderBtn && imageItems.length > 0) {
                const sortOrders = imageItems.map(item => item.sortOrder);
                const hasDuplicates = new Set(sortOrders).size !== sortOrders.length;
                const hasGaps = Math.max(...sortOrders) > imageItems.length || Math.min(...sortOrders) < 1;

                reorderBtn.style.display = (hasDuplicates || hasGaps) ? 'inline-block' : 'none';
            }
        }

        function addReorderButton() {
            const previewContainer = document.getElementById('imagesPreviewContainer');

            const oldBtn = document.getElementById('applyReorderBtn');
            if (oldBtn) oldBtn.parentElement.remove();

            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'mt-2 mb-3';
            buttonContainer.innerHTML = `
                <button type="button" id="applyReorderBtn" class="btn btn-warning btn-sm" style="display: none;">
                    <i class="fas fa-sort-amount-down"></i> Sắp xếp lại theo thứ tự đã chỉnh
                </button>
                <button type="button" id="autoSortBtn" class="btn btn-info btn-sm ms-2">
                    <i class="fas fa-sort-numeric-down"></i> Tự động sắp xếp 1 → ${imageItems.length}
                </button>
            `;

            if (previewContainer.firstChild) {
                previewContainer.insertBefore(buttonContainer, previewContainer.firstChild);
            } else {
                previewContainer.appendChild(buttonContainer);
            }

            document.getElementById('applyReorderBtn').addEventListener('click', function() {
                if (imageItems.length === 0) return;

                imageItems.sort((a, b) => a.sortOrder - b.sortOrder);

                imageItems.forEach((item, idx) => {
                    item.sortOrder = idx + 1;
                });

                const sortOrdersList = document.getElementById('sortOrdersList');
                const wrappers = Array.from(sortOrdersList.children);

                const wrapperMap = new Map();
                wrappers.forEach(wrapper => {
                    const id = wrapper.getAttribute('data-image-id');
                    wrapperMap.set(id, wrapper);
                });

                sortOrdersList.innerHTML = '';
                imageItems.forEach((item, idx) => {
                    const wrapper = wrapperMap.get(item.id);
                    if (wrapper) {
                        const sortInput = wrapper.querySelector('.sort-order-input');
                        if (sortInput) {
                            sortInput.value = item.sortOrder;
                            sortInput.max = imageItems.length;
                        }
                        sortOrdersList.appendChild(wrapper);
                    }
                });

                // Rebuild file input sau khi sắp xếp lại
                rebuildFileInput();

                document.getElementById('applyReorderBtn').style.display = 'none';
                showToast('Đã sắp xếp lại theo thứ tự bạn yêu cầu!', 'success');
            });

            document.getElementById('autoSortBtn').addEventListener('click', function() {
                if (imageItems.length === 0) return;

                imageItems.forEach((item, idx) => {
                    item.sortOrder = idx + 1;
                });

                const sortOrdersList = document.getElementById('sortOrdersList');
                const wrappers = Array.from(sortOrdersList.children);

                wrappers.forEach((wrapper, idx) => {
                    const sortInput = wrapper.querySelector('.sort-order-input');
                    if (sortInput) {
                        sortInput.value = idx + 1;
                    }
                });

                // Rebuild file input sau khi tự động sắp xếp
                rebuildFileInput();

                const applyBtn = document.getElementById('applyReorderBtn');
                if (applyBtn) applyBtn.style.display = 'none';

                showToast('Đã tự động sắp xếp từ 1 đến ' + imageItems.length, 'success');
            });
        }

        function showToast(message, type = 'info') {
            const toastContainer = document.querySelector('.toast-container');
            if (toastContainer) {
                // Bootstrap toast nếu có
            } else {
                alert(message);
            }
        }

        // ★ FIX 2: Hàm mới – rebuild file input từ imageItems hiện tại (không gán hidden inputs)
        // Dùng để đồng bộ file input sau mỗi lần xóa / sắp xếp ảnh
        function rebuildFileInput() {
            const dataTransfer = new DataTransfer();
            const sortedItems = [...imageItems].sort((a, b) => a.sortOrder - b.sortOrder);
            sortedItems.forEach(item => dataTransfer.items.add(item.file));
            document.getElementById('images').files = dataTransfer.files;
        }

        // ★ FIX 3: updateFileInput() – gán hidden inputs với idx+1 thay vì item.sortOrder
        // Sau khi sort, file[0] đã là ảnh ưu tiên nhất → sort_order[0]=1, sort_order[1]=2...
        function updateFileInput() {
            const sortedItems = [...imageItems].sort((a, b) => a.sortOrder - b.sortOrder);

            // Rebuild file input theo đúng thứ tự đã sort
            const dataTransfer = new DataTransfer();
            sortedItems.forEach(item => dataTransfer.items.add(item.file));
            document.getElementById('images').files = dataTransfer.files;

            // Xóa hidden inputs cũ
            removeHiddenSortInputs();

            // Gán idx+1 để server nhận sort_order liên tục: 1, 2, 3, ...
            sortedItems.forEach((item, idx) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `sort_order[${idx}]`;
                hiddenInput.value = idx + 1;
                hiddenInput.classList.add('sort-order-hidden');
                document.querySelector('form').appendChild(hiddenInput);
            });
        }

        function removeHiddenSortInputs() {
            const oldInputs = document.querySelectorAll('.sort-order-hidden');
            oldInputs.forEach(input => input.remove());
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const files = document.getElementById('images').files;

            if (files.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một ảnh để upload!');
                return false;
            }

            if (files.length > 10) {
                e.preventDefault();
                alert('Chỉ có thể upload tối đa 10 ảnh cùng lúc!');
                return false;
            }

            let isValid = true;
            Array.from(files).forEach(file => {
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} vượt quá giới hạn 2MB!`);
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                return false;
            }

            // Cập nhật file input và hidden sort_order trước khi submit
            updateFileInput();

            const btn = document.getElementById('btnSubmit');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải lên...';
            btn.disabled = true;
        });
    </script>

    <style>
        .sort-order-input {
            display: inline-block;
            width: 100px !important;
        }

        .remove-image:hover {
            opacity: 0.8;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        #applyReorderBtn,
        #autoSortBtn {
            margin-bottom: 10px;
        }
    </style>
@endsection
