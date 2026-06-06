@extends('layouts.app')

@section('title', 'Phản hồi Liên hệ')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Phản hồi Liên hệ #{{ $contact->id }}</h5>
        </div>

        <div class="card-body">
            <div class="alert alert-info">
                <strong>Người gửi:</strong> {{ $contact->full_name }} ({{ $contact->email }})
            </div>

            <h6 class="text-muted">Chủ đề: <strong>{{ $contact->subject }}</strong></h6>
            
            <div class="border p-3 bg-light rounded mb-4">
                <strong>Nội dung:</strong><br>
                {{ $contact->message }}
            </div>

            <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nội dung phản hồi <span class="text-danger">*</span></label>
                    <textarea 
                        name="reply" 
                        class="form-control" 
                        rows="8" 
                        placeholder="Viết phản hồi của bạn ở đây..." 
                        required>{{ old('reply', $contact->reply ?? '') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-send"></i> Gửi phản hồi
                    </button>
                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-secondary">
                        Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection