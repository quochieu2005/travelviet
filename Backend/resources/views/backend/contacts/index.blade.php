@extends('layouts.app')

@section('title', 'Quản lý Liên hệ - Admin')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách Liên hệ</h5>
        </div>

        @include('components._message')

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Chủ đề</th>
                        <th width="20%">Nội dung</th>
                        <th>Trạng thái</th>
                        <th width="10%">Ngày gửi</th>
                        <th width="8%">Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($contacts as $cs)
                        <tr>
                            <td>{{ $cs->id }}</td>
                            <td>
                                {{ $cs->full_name }}
                                @if($cs->user_id)
                                    <small class="text-success">(Đã đăng ký)</small>
                                @endif
                            </td>
                            <td>{{ $cs->email }}</td>
                            <td>{{ $cs->phone }}</td>
                            <td>{{ $cs->subject }}</td>
                            <td>
                                <small>{{ Str::limit($cs->message, 80) }}</small>
                            </td>
                            <td>
                                @switch($cs->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-info">Đang xử lý</span>
                                        @break
                                    @case('replied')
                                        <span class="badge bg-success">Đã phản hồi</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $cs->status }}</span>
                                @endswitch
                            </td>
                            <td>{{ $cs->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.contacts.show', $cs->id) }}">
                                            <i class="bx bx-show me-1"></i> Xem chi tiết
                                        </a>
                                        @if($cs->status !== 'replied')
                                            {{-- Sử dụng nút mở Modal thay vì link --}}
                                            <button type="button" class="dropdown-item text-primary" data-bs-toggle="modal" data-bs-target="#replyModal{{ $cs->id }}">
                                                <i class="bx bx-reply me-1"></i> Phản hồi
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal phản hồi cho từng liên hệ --}}
                        <div class="modal fade" id="replyModal{{ $cs->id }}" tabindex="-1" aria-labelledby="replyModalLabel{{ $cs->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="replyModalLabel{{ $cs->id }}">
                                            Phản hồi liên hệ #{{ $cs->id }} - {{ $cs->full_name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.contacts.reply', $cs->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Chủ đề:</strong> {{ $cs->subject }}<br>
                                                <strong>Nội dung:</strong><br>
                                                {{ $cs->message }}
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nội dung phản hồi <span class="text-danger">*</span></label>
                                                <textarea 
                                                    name="reply" 
                                                    class="form-control" 
                                                    rows="6" 
                                                    placeholder="Nhập nội dung phản hồi của bạn..."
                                                    required>{{ old('reply') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bx bx-send"></i> Gửi phản hồi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bx bx-inbox" style="font-size: 2rem;"></i><br>
                                Chưa có liên hệ nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="card-footer">
            {{ $contacts->appends(request()->query())->links() }}
        </div>
    </div>
@endsection