@extends('layouts.app')

@section('title', 'Chi tiết đơn đăng ký')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Thông tin chi tiết đơn đăng ký #{{ $inquiry->id }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Họ và tên:</th>
                                <td><strong>{{ $inquiry->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $inquiry->email }}</td>
                            </tr>
                            <tr>
                                <th>Số điện thoại:</th>
                                <td>{{ $inquiry->phone }}</td>
                            </tr>
                            <tr>
                                <th>Gói đăng ký:</th>
                                <td>
                                    <strong>{{ $inquiry->plan->name ?? 'Không xác định' }}</strong><br>
                                    @if ($inquiry->plan && $inquiry->plan->price)
                                        <span class="text-primary">Giá: {{ number_format($inquiry->plan->price) }}đ</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tin nhắn:</th>
                                <td>{{ $inquiry->message ?: 'Không có tin nhắn' }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    <form action="{{ route('admin.pricing-inquiries.update-status', $inquiry->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control d-inline-block w-auto"
                                            onchange="this.form.submit()">
                                            <option value="pending" {{ $inquiry->status == 'pending' ? 'selected' : '' }}>
                                                Chờ xử lý</option>
                                            <option value="contacted"
                                                {{ $inquiry->status == 'contacted' ? 'selected' : '' }}>Đã liên hệ</option>
                                            <option value="completed"
                                                {{ $inquiry->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                            <option value="cancelled"
                                                {{ $inquiry->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày đăng ký:</th>
                                <td>{{ $inquiry->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Cập nhật lần cuối:</th>
                                <td>{{ $inquiry->updated_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Thao tác</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.pricing-inquiries.index') }}" class="btn btn-secondary w-100 mb-2">
                            <i class="bx bx-arrow-back"></i> Quay lại danh sách
                        </a>

                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i class="bx bx-trash"></i> Xóa đơn đăng ký
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa đơn đăng ký của <strong>{{ $inquiry->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.pricing-inquiries.destroy', $inquiry->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
