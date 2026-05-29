@extends('layouts.app')
@section('title', 'Edit Tour - Admin TravelViet')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Edit Tour: {{ $tour->title }}</h5>
                            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                        <div class="card-body">

                            @php
                                // Xác định loại giảm giá người lớn
                                $calcAdultByPercent = $tour->price_adult > 0 && $tour->price_discount_percent <= 100
                                    ? (int) round($tour->price_adult * (1 - $tour->price_discount_percent / 100))
                                    : 0;
                                $isAdultPercent = $tour->price_discount_percent > 0
                                    && $tour->price_discount_percent <= 100
                                    && $calcAdultByPercent === (int) $tour->discount_price;
                                $isAdultFixed = $tour->discount_price > 0
                                    && $tour->discount_price < $tour->price_adult
                                    && !$isAdultPercent;
                                $adultDiscountAmount = $isAdultFixed
                                    ? ($tour->price_adult - $tour->discount_price)
                                    : 0;

                                // Xác định loại giảm giá trẻ em
                                $calcChildByPercent = $tour->price_child > 0 && $tour->price_child_discount_percent <= 100
                                    ? (int) round($tour->price_child * (1 - $tour->price_child_discount_percent / 100))
                                    : 0;
                                $isChildPercent = $tour->price_child_discount_percent > 0
                                    && $tour->price_child_discount_percent <= 100
                                    && $calcChildByPercent === (int) $tour->discount_price_child;
                                $isChildFixed = $tour->discount_price_child > 0
                                    && $tour->discount_price_child < $tour->price_child
                                    && !$isChildPercent;
                                $childDiscountAmount = $isChildFixed
                                    ? ($tour->price_child - $tour->discount_price_child)
                                    : 0;
                            @endphp

                            <form method="POST" action="{{ route('admin.tours.update', $tour->slug) }}" id="tourForm">
                                @csrf
                                @method('PUT')

                                <!-- Title & Slug -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <label class="form-label" for="title">Tour Title <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="bx bx-map"></i></span>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                id="title" name="title" value="{{ old('title', $tour->title) }}" required>
                                        </div>
                                        @error('title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="slug">Slug</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="bx bx-link"></i></span>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                                id="slug" name="slug" value="{{ old('slug', $tour->slug) }}">
                                        </div>
                                        <small class="form-text text-muted">Tự động sinh từ Title. Bạn có thể chỉnh tay.</small>
                                        @error('slug')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Short Description -->
                                <div class="mb-4">
                                    <label class="form-label" for="short_description">Mô tả ngắn</label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror"
                                        id="short_description" name="short_description" rows="2"
                                        placeholder="Mô tả ngắn gọn về tour...">{{ old('short_description', $tour->short_description) }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Full Description -->
                                <div class="mb-4">
                                    <label class="form-label" for="description">Mô tả chi tiết</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="6"
                                        placeholder="Chi tiết tour...">{{ old('description', $tour->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Giá Người Lớn -->
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label" for="price_adult">Giá Người Lớn (VNĐ) <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control price-input @error('price_adult') is-invalid @enderror"
                                            id="price_adult" name="price_adult"
                                            value="{{ old('price_adult', number_format($tour->price_adult, 0, ',', '.')) }}"
                                            required>
                                        @error('price_adult')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Kiểu giảm giá NL</label>
                                        <div class="d-flex gap-3 mt-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="discount_type_adult"
                                                    id="discount_type_adult_percent" value="percent"
                                                    {{ old('discount_type_adult', $isAdultPercent ? 'percent' : ($isAdultFixed ? 'fixed' : 'percent')) === 'percent' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="discount_type_adult_percent">Phần trăm (%)</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="discount_type_adult"
                                                    id="discount_type_adult_fixed" value="fixed"
                                                    {{ old('discount_type_adult', $isAdultPercent ? 'percent' : ($isAdultFixed ? 'fixed' : 'percent')) === 'fixed' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="discount_type_adult_fixed">Số tiền (VNĐ)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4" id="adult_percent_group">
                                        <label class="form-label" for="price_discount_percent">Giảm giá NL (%)</label>
                                        <input type="number" class="form-control" id="price_discount_percent"
                                            name="price_discount_percent" min="0" max="100" step="1"
                                            value="{{ old('price_discount_percent', $isAdultPercent ? $tour->price_discount_percent : 0) }}">
                                    </div>
                                    <div class="col-md-4 d-none" id="adult_fixed_group">
                                        <label class="form-label" for="discount_amount_adult">Giảm giá NL (VNĐ)</label>
                                        <input type="text" class="form-control price-input" id="discount_amount_adult"
                                            name="discount_amount_adult"
                                            value="{{ old('discount_amount_adult', $isAdultFixed ? number_format($adultDiscountAmount, 0, ',', '.') : '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="discount_price">Giá sau giảm NL (VNĐ)</label>
                                        <input type="text" class="form-control" id="discount_price"
                                            name="discount_price" readonly style="background-color: #f5f5f5;"
                                            value="{{ old('discount_price', $tour->discount_price && $tour->discount_price < $tour->price_adult ? number_format($tour->discount_price, 0, ',', '.') : '') }}">
                                        <small class="text-muted">Tự động tính toán</small>
                                    </div>
                                </div>

                                <!-- Giá Trẻ Em -->
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label" for="price_child">Giá Trẻ Em (VNĐ)</label>
                                        <input type="text"
                                            class="form-control price-input @error('price_child') is-invalid @enderror"
                                            id="price_child" name="price_child"
                                            value="{{ old('price_child', $tour->price_child ? number_format($tour->price_child, 0, ',', '.') : '') }}">
                                        @error('price_child')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Kiểu giảm giá TE</label>
                                        <div class="d-flex gap-3 mt-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="discount_type_child"
                                                    id="discount_type_child_percent" value="percent"
                                                    {{ old('discount_type_child', $isChildPercent ? 'percent' : ($isChildFixed ? 'fixed' : 'percent')) === 'percent' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="discount_type_child_percent">Phần trăm (%)</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="discount_type_child"
                                                    id="discount_type_child_fixed" value="fixed"
                                                    {{ old('discount_type_child', $isChildPercent ? 'percent' : ($isChildFixed ? 'fixed' : 'percent')) === 'fixed' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="discount_type_child_fixed">Số tiền (VNĐ)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4" id="child_percent_group">
                                        <label class="form-label" for="price_child_discount_percent">Giảm giá TE (%)</label>
                                        <input type="number" class="form-control" id="price_child_discount_percent"
                                            name="price_child_discount_percent" min="0" max="100" step="1"
                                            value="{{ old('price_child_discount_percent', $isChildPercent ? $tour->price_child_discount_percent : 0) }}">
                                    </div>
                                    <div class="col-md-4 d-none" id="child_fixed_group">
                                        <label class="form-label" for="discount_amount_child">Giảm giá TE (VNĐ)</label>
                                        <input type="text" class="form-control price-input" id="discount_amount_child"
                                            name="discount_amount_child"
                                            value="{{ old('discount_amount_child', $isChildFixed ? number_format($childDiscountAmount, 0, ',', '.') : '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="discount_price_child">Giá sau giảm TE (VNĐ)</label>
                                        <input type="text" class="form-control" id="discount_price_child"
                                            name="discount_price_child" readonly style="background-color: #f5f5f5;"
                                            value="{{ old('discount_price_child', $tour->discount_price_child && $tour->discount_price_child < $tour->price_child ? number_format($tour->discount_price_child, 0, ',', '.') : '') }}">
                                        <small class="text-muted">Tự động tính toán</small>
                                    </div>
                                </div>

                                <!-- Other Info -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label" for="duration_days">Số ngày</label>
                                        <input type="number" class="form-control @error('duration_days') is-invalid @enderror"
                                            id="duration_days" name="duration_days" min="1"
                                            value="{{ old('duration_days', $tour->duration_days) }}">
                                        @error('duration_days')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="max_people">Số người tối đa</label>
                                        <input type="number" class="form-control @error('max_people') is-invalid @enderror"
                                            id="max_people" name="max_people" min="1"
                                            value="{{ old('max_people', $tour->max_people) }}">
                                        @error('max_people')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="availability">Số chỗ còn lại</label>
                                        <input type="number" class="form-control @error('availability') is-invalid @enderror"
                                            id="availability" name="availability" min="0"
                                            value="{{ old('availability', $tour->availability) }}">
                                        @error('availability')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Dates & Location -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label" for="departure_location">Điểm xuất phát</label>
                                        <input type="text" class="form-control @error('departure_location') is-invalid @enderror"
                                            id="departure_location" name="departure_location" placeholder="Hồ Chí Minh"
                                            value="{{ old('departure_location', $tour->departure_location) }}">
                                        @error('departure_location')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="start_date">Ngày bắt đầu</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                            id="start_date" name="start_date"
                                            value="{{ old('start_date', $tour->start_date ? $tour->start_date->format('Y-m-d') : '') }}">
                                        @error('start_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="end_date">Ngày kết thúc</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                            id="end_date" name="end_date"
                                            value="{{ old('end_date', $tour->end_date ? $tour->end_date->format('Y-m-d') : '') }}">
                                        @error('end_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Destination & Category -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label" for="destination_id">Điểm đến <span class="text-danger">*</span></label>
                                        <select class="form-select @error('destination_id') is-invalid @enderror"
                                            id="destination_id" name="destination_id" required>
                                            <option value="">-- Chọn điểm đến --</option>
                                            @foreach ($destinations as $destination)
                                                <option value="{{ $destination->id }}"
                                                    {{ old('destination_id', $tour->destination_id) == $destination->id ? 'selected' : '' }}>
                                                    {{ $destination->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('destination_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="category_id">Danh mục <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id" required>
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $tour->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Itinerary -->
                                <div class="mb-4">
                                    <label class="form-label" for="itinerary">Lịch trình</label>
                                    <textarea class="form-control @error('itinerary') is-invalid @enderror"
                                        id="itinerary" name="itinerary" rows="5"
                                        placeholder="Chi tiết lịch trình tour...">{{ old('itinerary', $tour->itinerary) }}</textarea>
                                    @error('itinerary')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Services -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Dịch vụ bao gồm</label>
                                        <textarea class="form-control" name="included_services[]" rows="4"
                                            placeholder="Khách sạn 4 sao&#10;Xe limousine&#10;Bữa sáng + trưa + tối&#10;HDV tiếng Việt">{{ is_array($tour->included_services) ? implode("\n", $tour->included_services) : '' }}</textarea>
                                        <small class="text-muted">Mỗi dòng một dịch vụ</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Dịch vụ không bao gồm</label>
                                        <textarea class="form-control" name="excluded_services[]" rows="4"
                                            placeholder="Vé máy bay&#10;Chi phí cá nhân&#10;Tip hướng dẫn viên&#10;Đồ uống trong bữa ăn">{{ is_array($tour->excluded_services) ? implode("\n", $tour->excluded_services) : '' }}</textarea>
                                        <small class="text-muted">Mỗi dòng một dịch vụ</small>
                                    </div>
                                </div>

                                <!-- SEO -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label" for="meta_title">Meta Title (SEO)</label>
                                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                            id="meta_title" name="meta_title"
                                            value="{{ old('meta_title', $tour->meta_title) }}">
                                        @error('meta_title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="meta_description">Meta Description</label>
                                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                            id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $tour->meta_description) }}</textarea>
                                        @error('meta_description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="form-label">Trạng thái</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status"
                                            value="1" {{ old('status', $tour->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Hoạt động</label>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary me-2">Cập nhật Tour</button>
                                    <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Hủy</a>
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
    // ==================== HÀM TIỆN ÍCH ====================
    function getRawNumber(value) {
        if (!value) return 0;
        return parseInt(value.toString().replace(/[^0-9]/g, '')) || 0;
    }

    function formatNumber(value) {
        if (!value || value == 0) return '';
        return getRawNumber(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // ==================== SLUG ====================
    function stringToSlug(str) {
        str = str.toLowerCase();
        str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        str = str.replace(/[đĐ]/g, 'd');
        str = str.replace(/([^0-9a-z-\s])/g, '');
        str = str.replace(/\s+/g, '-');
        str = str.replace(/^-+|-+$/g, '');
        return str;
    }

    const titleInput = document.getElementById('title');
    const slugInput  = document.getElementById('slug');
    let isSlugManuallyEdited = true; // edit form: mặc định không tự ghi đè slug

    if (slugInput) {
        slugInput.addEventListener('input', () => isSlugManuallyEdited = true);
        slugInput.addEventListener('blur', function () {
            if (this.value.trim() === '' && titleInput?.value.trim() !== '') {
                slugInput.value = stringToSlug(titleInput.value);
                isSlugManuallyEdited = false;
            }
        });
    }

    if (titleInput) {
        titleInput.addEventListener('input', function () {
            if (!isSlugManuallyEdited && slugInput) {
                slugInput.value = stringToSlug(this.value);
            }
        });
    }

    // ==================== FORMAT GIÁ TIỀN ====================
    function bindPriceFormatting(el) {
        if (!el) return;
        el.addEventListener('input',  () => el.value = el.value.replace(/[^0-9]/g, ''));
        el.addEventListener('blur',   () => { const v = getRawNumber(el.value); el.value = v > 0 ? formatNumber(v) : ''; });
        el.addEventListener('focus',  () => { const v = getRawNumber(el.value); el.value = v > 0 ? v : ''; });
    }

    document.querySelectorAll('.price-input').forEach(bindPriceFormatting);

    // ==================== TÍNH GIÁ SAU GIẢM ====================
    function recalcDiscount(priceId, radioName, percentId, fixedId, resultId) {
        const price    = getRawNumber(document.getElementById(priceId)?.value);
        const resultEl = document.getElementById(resultId);
        const type     = document.querySelector(`input[name="${radioName}"]:checked`)?.value;

        if (!resultEl || !type || price <= 0) {
            if (resultEl) resultEl.value = '';
            return;
        }

        let finalPrice = price;

        if (type === 'percent') {
            let pct = Math.min(100, Math.max(0, parseFloat(document.getElementById(percentId)?.value) || 0));
            finalPrice = Math.round(price * (1 - pct / 100));
        } else {
            let fixed = getRawNumber(document.getElementById(fixedId)?.value);
            finalPrice = Math.max(0, price - fixed);
        }

        resultEl.value = (finalPrice > 0 && finalPrice < price) ? formatNumber(finalPrice) : '';
        resultEl.setAttribute('data-raw', finalPrice);
    }

    // ==================== TOGGLE % / VNĐ ====================
    function bindDiscountTypeToggle(radioName, percentGroupId, fixedGroupId, priceId, percentId, fixedId, resultId) {
        const radios       = document.querySelectorAll(`input[name="${radioName}"]`);
        const percentGroup = document.getElementById(percentGroupId);
        const fixedGroup   = document.getElementById(fixedGroupId);

        if (!radios.length || !percentGroup || !fixedGroup) return;

        function applyState(isPercent) {
            percentGroup.classList.toggle('d-none', !isPercent);
            fixedGroup.classList.toggle('d-none', isPercent);
        }

        const checked = document.querySelector(`input[name="${radioName}"]:checked`);
        applyState(checked?.value === 'percent');

        radios.forEach(radio => {
            radio.addEventListener('change', function () {
                const isPercent = this.value === 'percent';
                applyState(isPercent);

                if (isPercent) {
                    const el = document.getElementById(fixedId);
                    if (el) el.value = '';
                } else {
                    const el = document.getElementById(percentId);
                    if (el) el.value = 0;
                }

                recalcDiscount(priceId, radioName, percentId, fixedId, resultId);
            });
        });
    }

    // ==================== NGƯỜI LỚN ====================
    bindDiscountTypeToggle(
        'discount_type_adult', 'adult_percent_group', 'adult_fixed_group',
        'price_adult', 'price_discount_percent', 'discount_amount_adult', 'discount_price'
    );

    ['price_adult', 'price_discount_percent', 'discount_amount_adult'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        ['input', 'blur'].forEach(evt => {
            el.addEventListener(evt, () => recalcDiscount(
                'price_adult', 'discount_type_adult',
                'price_discount_percent', 'discount_amount_adult', 'discount_price'
            ));
        });
    });

    // ==================== TRẺ EM ====================
    bindDiscountTypeToggle(
        'discount_type_child', 'child_percent_group', 'child_fixed_group',
        'price_child', 'price_child_discount_percent', 'discount_amount_child', 'discount_price_child'
    );

    ['price_child', 'price_child_discount_percent', 'discount_amount_child'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        ['input', 'blur'].forEach(evt => {
            el.addEventListener(evt, () => recalcDiscount(
                'price_child', 'discount_type_child',
                'price_child_discount_percent', 'discount_amount_child', 'discount_price_child'
            ));
        });
    });

    // ==================== SUBMIT ====================
    document.getElementById('tourForm')?.addEventListener('submit', function () {

        // Làm sạch giá gốc
        ['price_adult', 'price_child'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = getRawNumber(el.value);
        });

        // --- Người lớn ---
        const typeAdult       = document.querySelector('input[name="discount_type_adult"]:checked')?.value || 'percent';
        const percentAdultEl  = document.getElementById('price_discount_percent');
        const fixedAdultEl    = document.getElementById('discount_amount_adult');
        const discountPriceEl = document.getElementById('discount_price');

        if (typeAdult === 'percent') {
            if (percentAdultEl) percentAdultEl.value = parseInt(percentAdultEl.value) || 0;
            if (fixedAdultEl)   fixedAdultEl.removeAttribute('name');
        } else {
            if (fixedAdultEl)   fixedAdultEl.value = getRawNumber(fixedAdultEl.value);
            if (percentAdultEl) percentAdultEl.removeAttribute('name');
        }
        if (discountPriceEl) discountPriceEl.removeAttribute('name');

        // --- Trẻ em ---
        const typeChild            = document.querySelector('input[name="discount_type_child"]:checked')?.value || 'percent';
        const percentChildEl       = document.getElementById('price_child_discount_percent');
        const fixedChildEl         = document.getElementById('discount_amount_child');
        const discountPriceChildEl = document.getElementById('discount_price_child');

        if (typeChild === 'percent') {
            if (percentChildEl) percentChildEl.value = parseInt(percentChildEl.value) || 0;
            if (fixedChildEl)   fixedChildEl.removeAttribute('name');
        } else {
            if (fixedChildEl)   fixedChildEl.value = getRawNumber(fixedChildEl.value);
            if (percentChildEl) percentChildEl.removeAttribute('name');
        }
        if (discountPriceChildEl) discountPriceChildEl.removeAttribute('name');
    });
</script>
@endsection