@extends('layouts.app')

@section('title', 'Blog Categories - Admin TravelViet')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Blog Categories Management</h5>
            <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
                <i class="icon-base bx bx-plus-circle me-1"></i> Create Blog Category
            </a>
        </div>

        @include('components._message')

        <!-- Filter Section -->
        <div class="card-body border-bottom">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search by name..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info">
                        <i class="icon-base bx bx-filter-alt me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                        <i class="icon-base bx bx-reset me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Posts Count</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">

                    @forelse ($categories as $cs)
                        <tr>

                            <td>
                                <strong>{{ $cs->name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-label-secondary">{{ $cs->slug }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-info">
                                    <i class="icon-base bx bx-news me-1"></i> {{ $cs->posts()->count() }} posts
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.blog-categories.toggle-status', $cs->slug) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="border-0 bg-transparent p-0">

                                        @if ($cs->status == 1)
                                            <span class="badge bg-label-success me-1">
                                                <i class="icon-base bx bx-check-circle me-1"></i>
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-label-danger me-1">
                                                <i class="icon-base bx bx-x-circle me-1"></i>
                                                Inactive
                                            </span>
                                        @endif

                                    </button>
                                </form>
                            </td>
                            <td>{{ $cs->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('admin.blog-categories.edit', $cs->slug) }}">
                                            <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $cs->id }}">
                                            <i class="icon-base bx bx-trash me-1"></i> Delete
                                        </button>
                                    </div>

                                    <!-- Modal xác nhận xóa -->
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
                                                    @if ($cs->posts()->count() > 0)
                                                        <p class="text-center text-danger small mt-2">
                                                            <i class="icon-base bx bx-error me-1"></i>
                                                            Warning: This category has {{ $cs->posts()->count() }} post(s)!
                                                        </p>
                                                    @endif
                                                    <p class="text-center text-muted small">This action cannot be undone!
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        <i class="icon-base bx bx-x me-1"></i> Cancel
                                                    </button>
                                                    <form action="{{ route('admin.blog-categories.destroy', $cs->slug) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            {{ $cs->posts()->count() > 0 ? 'disabled' : '' }}>
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
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="icon-base bx bx-folder-open fs-1 text-muted"></i>
                                <p class="mt-2 mb-0">No blog categories found!</p>
                                <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-sm btn-primary mt-2">
                                    Create first category
                                </a>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $categories->links() }}
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Auto hide alert after 3 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    </script>
@endsection
