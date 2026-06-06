@extends('layouts.app')

@section('title', 'Danh sách đăng ký gói giá')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Danh sách khách hàng đăng ký gói giá</h4>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Gói đăng ký</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng ký</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $inquiry)
                        <tr>
                            <td>{{ $inquiry->id }}</td>
                            <td>{{ $inquiry->name }}</td>
                            <td>{{ $inquiry->email }}</td>
                            <td>{{ $inquiry->phone }}</td>
                            <td>{{ $inquiry->plan->name ?? 'Không xác định' }}</td>
                            <td>
                                @if($inquiry->plan && $inquiry->plan->price)
                                    {{ number_format($inquiry->plan->price) }}đ
                                @else
                                    Liên hệ
                                @endif
                            </td>
                            <td>
                                @if($inquiry->status == 'pending')
                                    <span class="badge bg-warning">Chờ xử lý</span>
                                @elseif($inquiry->status == 'contacted')
                                    <span class="badge bg-info">Đã liên hệ</span>
                                @elseif($inquiry->status == 'completed')
                                    <span class="badge bg-success">Hoàn thành</span>
                                @else
                                    <span class="badge bg-secondary">{{ $inquiry->status }}</span>
                                @endif
                            </td>
                            <td>{{ $inquiry->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.pricing-inquiries.show', $inquiry->id) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="bx bx-show"></i> Xem
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $inquiry->id }}">
                                        <i class="bx bx-trash"></i> Xóa
                                    </button>
                                </div>
                                
                                <!-- Modal xóa -->
                                <div class="modal fade" id="deleteModal{{ $inquiry->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Xác nhận xóa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Bạn có chắc muốn xóa đơn đăng ký của <strong>{{ $inquiry->name }}</strong>?
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Chưa có đơn đăng ký nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $inquiries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection