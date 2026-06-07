@extends('layouts.app')

@section('title', 'Transports - Admin TravelViet')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transport Table</h5>

        <a href="{{ route('admin.transports.create') }}" class="btn btn-primary">
            Create Transport
        </a>
    </div>

    @include('components._message')

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Destination</th>
                    <th>Mileage</th>
                    <th>Transmission</th>
                    <th>Seats</th>
                    <th>Trips</th>
                    <th>Rating</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0">
                @forelse($transports as $transport)
                    <tr>
                        {{-- Vehicle --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <img 
                                    src="{{ $transport->image ?? asset('assets/img/avatars/1.png') }}" 
                                    alt="{{ $transport->name }}" 
                                    class="rounded me-3" 
                                    width="60" 
                                    height="60" 
                                    style="object-fit:cover"
                                >
                                <div>
                                    <span class="fw-medium d-block">
                                        {{ $transport->name }}
                                    </span>
                                    <small class="text-muted text-break" style="max-width: 150px; display: block;">
                                        {{ $transport->slug }}
                                    </small>
                                </div>
                            </div>
                        </td>

                        {{-- Destination --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <img 
                                    src="{{ $transport->destination?->image ?? asset('assets/img/avatars/1.png') }}" 
                                    alt="{{ $transport->destination?->name ?? 'N/A' }}" 
                                    class="rounded-circle me-2" 
                                    width="40" 
                                    height="40" 
                                    style="object-fit:cover"
                                >
                                <span>
                                    {{ $transport->destination?->name ?? 'N/A' }}
                                </span>
                            </div>
                        </td>

                        {{-- Mileage --}}
                        <td>
                            {{ $transport->mileage ?? 'N/A' }}
                        </td>

                        {{-- Transmission --}}
                        <td>
                            @if($transport->transmission)
                                <span class="badge bg-label-info">
                                    {{ $transport->transmission }}
                                </span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        {{-- Seats --}}
                        <td>
                            {{ $transport->seats ?? 'N/A' }}
                        </td>

                        {{-- Trips --}}
                        <td>
                            {{ number_format($transport->trips ?? 0) }}
                        </td>

                        {{-- Rating --}}
                        <td>
                            <span class="text-warning">
                                ★ {{ $transport->rating ?? '5.0' }}
                            </span>
                            <small class="d-block text-muted">
                                {{ $transport->review ?? 0 }} reviews
                            </small>
                        </td>

                        {{-- Price --}}
                        <td>
                            <span class="fw-medium text-success">
                                {{ number_format($transport->price, 0, ',', '.') }} ₫
                            </span>
                        </td>

                        {{-- Status --}}
                        <td>
                            {{-- Đã đổi thành badge hiển thị tĩnh vì Controller hiện tại không có hàm toggleStatus --}}
                            @if(isset($transport->status) && $transport->status)
                                <span class="badge bg-label-success">Active</span>
                            @else
                                <span class="badge bg-label-danger">Inactive</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                </button>
                                
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.transports.edit', $transport->slug) }}">
                                        <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    
                                    <div class="dropdown-divider"></div>
                                    
                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $transport->id }}">
                                        <i class="icon-base bx bx-trash me-1"></i> Delete
                                    </button>
                                </div>

                                {{-- Delete Modal --}}
                                <div class="modal fade" id="deleteModal{{ $transport->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <p class="text-center">
                                                    Are you sure you want to delete <strong>{{ $transport->name }}</strong>?
                                                </p>
                                            </div>
                                            
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                
                                                <form action="{{ route('admin.transports.destroy', $transport->slug) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <div class="text-muted">No transports found.</div>
                            <a href="{{ route('admin.transports.create') }}" class="btn btn-primary btn-sm mt-2">
                                Create your first transport
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if(isset($transports) && method_exists($transports, 'links'))
<div class="mt-3 d-flex justify-content-end">
    {{ $transports->links() }}
</div>
@endif
@endsection