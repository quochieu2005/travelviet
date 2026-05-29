@extends('layouts.app')

@section('title', 'Create Tour Schedule - Admin TravelViet')

@section('content')

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xxl">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Create Tour Schedule</h5>
                            <a href="{{ route('admin.tour-schedules.index') }}" class="btn btn-secondary btn-sm">Back to
                                List</a>
                        </div>

                        @include('components._message')

                        <div class="card-body">
                            <form action="{{ route('admin.tour-schedules.store') }}" method="POST">
                                @csrf

                                {{-- Select Tour --}}
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="tour_id">
                                        Tour <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select name="tour_id" id="tour_id" class="form-select" required>
                                            <option value="">-- Select Tour --</option>
                                            @foreach ($tours as $tour)
                                                <option value="{{ $tour->id }}"
                                                    data-max-people="{{ $tour->max_people }}"
                                                    data-price-adult="{{ $tour->price_adult }}"
                                                    data-price-child="{{ $tour->price_child }}"
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

                                {{-- Departure Date --}}
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="departure_date">
                                        Departure Date <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="departure_date" name="departure_date"
                                            value="{{ old('departure_date') }}" min="{{ date('Y-m-d') }}" required />
                                        @error('departure_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Return Date --}}
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="return_date">
                                        Return Date <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="return_date" name="return_date"
                                            value="{{ old('return_date') }}" required />
                                        @error('return_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Available Slots --}}
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="available_slots">
                                        Available Slots <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="available_slots"
                                            name="available_slots" value="{{ old('available_slots') }}" min="0"
                                            required />
                                        <div class="form-text text-muted" id="slots-hint"></div>
                                        @error('available_slots')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Price Override (người lớn + trẻ em) --}}
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label">Price Override</label>
                                    <div class="col-sm-10">
                                        <div class="row g-3">
                                            {{-- Người lớn --}}
                                            <div class="col-sm-6">
                                                <label class="form-label text-muted" style="font-size: 12px;">
                                                    Người lớn
                                                </label>
                                                <input type="number" class="form-control" id="price_override"
                                                    name="price_override" value="{{ old('price_override') }}"
                                                    step="1000" min="0" placeholder="Để trống = dùng giá gốc" />
                                                <div class="form-text text-muted" id="price-hint-adult"></div>
                                                @error('price_override')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Trẻ em --}}
                                            <div class="col-sm-6">
                                                <label class="form-label text-muted" style="font-size: 12px;">
                                                    Trẻ em
                                                </label>
                                                <input type="number" class="form-control" id="price_override_child"
                                                    name="price_override_child" value="{{ old('price_override_child') }}"
                                                    step="1000" min="0" placeholder="Để trống = dùng giá gốc" />
                                                <div class="form-text text-muted" id="price-hint-child"></div>
                                                @error('price_override_child')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Note --}}
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="note">Ghi chú</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="note" name="note"
                                            value="{{ old('note') }}"
                                            placeholder="VD: Lễ 30/4, Flash sale tháng 6, Tết Nguyên Đán..." />
                                        @error('note')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Tạo lịch khởi hành
                                        </button>
                                        <a href="{{ route('admin.tour-schedules.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Hủy
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
        const tourSelect = document.getElementById('tour_id');
        const departureInput = document.getElementById('departure_date');
        const returnInput = document.getElementById('return_date');

        tourSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const maxPeople = selected.dataset.maxPeople;
            const priceAdult = selected.dataset.priceAdult;
            const priceChild = selected.dataset.priceChild;

            if (maxPeople) {
                document.getElementById('available_slots').value = maxPeople;
                document.getElementById('slots-hint').textContent = 'Lấy từ max_people của tour: ' + maxPeople +
                    ' người';
            }

            document.getElementById('price-hint-adult').textContent = priceAdult ?
                'Giá gốc: ' + Number(priceAdult).toLocaleString('vi-VN') + ' ₫' :
                '';

            document.getElementById('price-hint-child').textContent = priceChild && priceChild > 0 ?
                'Giá gốc: ' + Number(priceChild).toLocaleString('vi-VN') + ' ₫' :
                'Tour này không có giá trẻ em';
        });

        departureInput.addEventListener('change', function() {
            returnInput.min = this.value;

            // Nếu return_date đang nhỏ hơn departure_date thì reset
            if (returnInput.value && returnInput.value < this.value) {
                returnInput.value = this.value;
            }
        });
    </script>
@endsection
