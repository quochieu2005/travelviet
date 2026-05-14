@extends('layouts.app')

@section('title', 'Categories - Admin TravelViet')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Categories Table head</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Create Categories</a>
        </div>

        @include('components._message')

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">

                    @foreach ($categories as $cs)
                        <tr>
                            <td>
                                <img src="{{ $cs->image ? $cs->image : asset('assets/img/avatars/1.png') }}"
                                    alt="{{ $cs->name }}" class="rounded-circle" width="50" height="50"
                                    style="object-fit: cover;" />
                            </td>
                            <td>
                                <span>{{ $cs->name }}</span>
                            </td>
                            <td>{{ Str::limit($cs->description, 50) }}</td>
                            <td>
                                <form action="{{ route('admin.categories.toggle-status', $cs->slug) }}" method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="border-0 bg-transparent p-0">

                                        @if ($cs->status == 'active')
                                            <span class="badge bg-label-success me-1">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-label-danger me-1">
                                                Inactive
                                            </span>
                                        @endif

                                    </button>
                                </form>
                            </td>
                            <td>{{ $cs->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('admin.categories.edit', ['categories' => $cs->slug]) }}">
                                            <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $cs->id }}">
                                            <i class="icon-base bx bx-trash me-1"></i> Delete
                                        </button>
                                    </div>

                                    <!-- Modal xác nhận xóa cho từng Categories -->
                                    <div class="modal fade" id="deleteModal{{ $cs->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">
                                                        <i class="icon-base bx bx-trash me-1"></i> Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        <i class="icon-base bx bx-question-mark fs-1"
                                                            style="font-size: 48px; color: #ffc107;"></i>
                                                    </div>
                                                    <p class="text-center mb-0">Are you sure you want to delete
                                                        <strong>{{ $cs->name }}</strong>?
                                                    </p>
                                                    <p class="text-center text-muted small">This action cannot be undone!
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        <i class="icon-base bx bx-x me-1"></i> Cancel
                                                    </button>
                                                    <form action="{{ route('admin.categories.destroy', $cs->slug) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="icon-base bx bx-trash me-1"></i> Yes, Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
