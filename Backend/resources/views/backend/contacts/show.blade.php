@extends('layouts.app')

@section('title', 'Chi tiết Liên hệ')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Chi tiết Liên hệ #{{ $contact->id }}</h5>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Quay lại
            </a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Thông tin người gửi</h6>
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">Họ và tên:</th>
                            <td>{{ $contact->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $contact->email }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $contact->phone }}</td>
                        </tr>
                        <tr>
                            <th>Ngày gửi:</th>
                            <td>{{ $contact->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Trạng thái</h6>
                    <p>
                        @switch($contact->status)
                            @case('pending')
                                <span class="badge bg-warning fs-6">Chờ xử lý</span>
                                @break
                            @case('processing')
                                <span class="badge bg-info fs-6">Đang xử lý</span>
                                @break
                            @case('replied')
                                <span class="badge bg-success fs-6">Đã phản hồi</span>
                                @break
                        @endswitch
                    </p>
                </div>
            </div>

            <hr>

            <h6 class="text-muted">Chủ đề</h6>
            <p class="fs-5 fw-bold">{{ $contact->subject }}</p>

            <h6 class="text-muted">Nội dung tin nhắn</h6>
            <div class="border p-3 bg-light rounded">
                {{ $contact->message }}
            </div>

            @if($contact->reply)
                <hr>
                <h6 class="text-muted">Phản hồi của Admin</h6>
                <div class="border p-3 bg-success bg-opacity-10 rounded">
                    <p>{{ $contact->reply }}</p>
                    <small class="text-muted">Phản hồi lúc: {{ $contact->replied_at->format('d/m/Y H:i') }}</small>
                </div>
            @endif
        </div>

        <div class="card-footer">
            @if($contact->status !== 'replied')
                <a href="{{ route('admin.contacts.reply', $contact->id) }}" class="btn btn-primary">
                    <i class="bx bx-reply"></i> Phản hồi ngay
                </a>
            @endif
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
    </div>
@endsection