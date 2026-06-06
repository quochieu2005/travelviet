@extends('layouts.app')

@section('title', 'Quản lý gói giá')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">Gói giá</h4>
        <a href="{{ route('admin.pricing.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Thêm gói mới
        </a>
    </div>

    {{-- Toast --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Bảng danh sách --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tên gói</th>
                        <th>Giá</th>
                        <th>Tính năng</th>
                        <th>Popular</th>
                        <th>Thứ tự</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                    <tr>
                        <td>
                            <strong>{{ $plan->name }}</strong>
                            <br><small class="text-muted">{{ $plan->description }}</small>
                        </td>
                        <td>
                            @if($plan->price)
                                {{ number_format($plan->price, 0, ',', '.') }}đ
                                <br><small class="text-muted">{{ $plan->price_note }}</small>
                            @else
                                <span class="text-muted">Liên hệ</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                ✅ {{ count($plan->features ?? []) }} tính năng<br>
                                ❌ {{ count($plan->disabled_features ?? []) }} bị tắt
                            </small>
                        </td>
                        <td>
                            @if($plan->is_popular)
                                <span class="badge bg-warning text-dark">Popular</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $plan->order }}</td>
                        <td>
                            @if($plan->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.pricing.edit', $plan) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="{{ route('admin.pricing.destroy', $plan) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Xoá gói {{ $plan->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Chưa có gói giá nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection