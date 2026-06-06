@extends('layouts.app')

@section('title', 'Quản lý Khách sạn')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Hotels</h4>
        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">+ Add Hotel</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Thumbnail</th>
                        <th>Name</th>
                        <th>Destination</th>
                        <th>Price (₫)</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hotels as $hotel)
                    <tr>
                        <td>
                            @if($hotel->thumbnail)
                                <img src="{{ $hotel->thumbnail }}" width="70" class="rounded">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>{{ $hotel->name }}</td>
                        <td>{{ $hotel->destination->name ?? '-' }}</td>
                        <td>{{ number_format($hotel->price, 0, ',', '.') }}₫</td>
                        <td>⭐ {{ $hotel->rating }}</td>
                        <td>
                            @if($hotel->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.hotels.edit', $hotel->slug) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.hotels.destroy', $hotel->slug) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this hotel?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No hotels found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $hotels->links() }}</div>
</div>
@endsection