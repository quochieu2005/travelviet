@extends('layouts.app')

@section('title', 'Edit Promotion - Admin TravelViet')

@section('content')

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-6 gy-6">
                <div class="col-xxl">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Edit Promotion</h5>
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                        </div>

                        @include('components._message')

                        <div class="card-body">
                            <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Code -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="code">Promotion Code <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                            id="code" name="code" value="{{ old('code', $promotion->code) }}"
                                            placeholder="e.g., SUMMER2025, WELCOME10" />
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Unique code. Must be different from existing promotions.
                                        </div>
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="type">Discount Type <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select id="type" class="form-select @error('type') is-invalid @enderror"
                                            name="type">
                                            <option value="">Select type</option>
                                            <option value="percentage"
                                                {{ old('type', $promotion->type) == 'percentage' ? 'selected' : '' }}>
                                                Percentage (%)</option>
                                            <option value="fixed_amount"
                                                {{ old('type', $promotion->type) == 'fixed_amount' ? 'selected' : '' }}>
                                                Fixed Amount (VND)
                                            </option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Value -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="value">Discount Value <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01"
                                            class="form-control @error('value') is-invalid @enderror" id="value"
                                            name="value" value="{{ old('value', $promotion->value) }}"
                                            placeholder="e.g., 10 (for 10%) or 50000 (for 50,000 VND)" />
                                        @error('value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Start Date -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="start_date">Start Date <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                            id="start_date" name="start_date"
                                            value="{{ old('start_date', $promotion->start_date ? \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d') : '') }}" />
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- End Date -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="end_date">End Date <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                            id="end_date" name="end_date"
                                            value="{{ old('end_date', $promotion->end_date ? \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d') : '') }}" />
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Must be after or equal to start date.</div>
                                    </div>
                                </div>

                                <!-- Min Order -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="min_order">Minimum Order Amount</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01"
                                            class="form-control @error('min_order') is-invalid @enderror" id="min_order"
                                            name="min_order" value="{{ old('min_order', $promotion->min_order) }}"
                                            placeholder="Leave empty for no minimum requirement" />
                                        @error('min_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimum order value to apply this promotion.</div>
                                    </div>
                                </div>

                                <!-- Usage Limit -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="usage_limit">Usage Limit</label>
                                    <div class="col-sm-10">
                                        <input type="number"
                                            class="form-control @error('usage_limit') is-invalid @enderror" id="usage_limit"
                                            name="usage_limit" value="{{ old('usage_limit', $promotion->usage_limit) }}"
                                            placeholder="Leave empty for unlimited uses" />
                                        @error('usage_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Maximum number of times this promotion can be used.</div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="row mb-6">
                                    <label class="col-sm-2 col-form-label" for="status">Status <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select id="status" class="form-select @error('status') is-invalid @enderror"
                                            name="status">
                                            <option value="active"
                                                {{ old('status', $promotion->status) == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ old('status', $promotion->status) == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Update Promotion</button>
                                        <a href="{{ route('admin.promotions.index') }}"
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
