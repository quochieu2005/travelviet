@extends('layouts.app')

@section('title', 'Images Tour - Admin TravelViet')

@section('content')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="icon-base bx bx-images me-1"></i> Images Tour
                </h5>
                <div>
                    <!-- Nút Delete All -->
                    <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
                        <i class="icon-base bx bx-trash-alt me-1"></i> Delete All Images
                    </button>
                    <a href="{{ route('admin.image-tours.create') }}" class="btn btn-primary">
                        <i class="icon-base bx bx-plus me-1"></i> Create Image
                    </a>
                </div>
            </div>

            @include('components._message')

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tour Name</th>
                            <th>Image</th>
                            <th>Type</th>
                            <th>Sort Order</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($tourImages as $index => $image)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $image->tour->title ?? ($image->tour->name ?? 'N/A') }}</strong>
                                 </td>
                                <td>
                                    <img src="{{ $image->image ?: asset('assets/img/avatars/1.png') }}" alt="Tour Image"
                                        class="rounded" width="60" height="60"
                                        style="object-fit: cover; border: 1px solid #ddd;" />
                                 </td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $image->type ?? 'tour' }}</span>
                                 </td>
                                <td>
                                    <span class="badge {{ $image->sort_order == 1 ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="icon-base bx bx-sort-alt-2 me-1"></i>
                                        {{ $image->sort_order }}
                                    </span>
                                 </td>
                                <td>{{ $image->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.image-tours.edit', $image->id) }}">
                                                <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <button type="button" class="dropdown-item text-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $image->id }}">
                                                <i class="icon-base bx bx-trash me-1"></i> Delete
                                            </button>
                                            @if($image->tour)
                                            <button type="button" class="dropdown-item text-warning"
                                                data-bs-toggle="modal" data-bs-target="#deleteAllTourModal{{ $image->tour->id }}">
                                                <i class="icon-base bx bx-trash-alt me-1"></i> Delete All Images of this Tour
                                            </button>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Modal xóa 1 ảnh -->
                                    <div class="modal fade" id="deleteModal{{ $image->id }}" tabindex="-1"
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
                                                    <p class="text-center mb-0">Are you sure you want to delete this
                                                        image?</p>
                                                    <p class="text-center text-muted small">
                                                        <strong>Tour:</strong>
                                                        {{ $image->tour->title ?? ($image->tour->name ?? 'N/A') }}
                                                        <br>
                                                        <strong>Sort Order:</strong> {{ $image->sort_order }}
                                                    </p>
                                                    <p class="text-center text-danger small mt-2">
                                                        <i class="icon-base bx bx-error-circle"></i> This action cannot
                                                        be undone!
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        <i class="icon-base bx bx-x me-1"></i> Cancel
                                                    </button>
                                                    <form action="{{ route('admin.image-tours.destroy', $image->id) }}"
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

                                    <!-- Modal xóa tất cả ảnh của 1 tour -->
                                    @if($image->tour)
                                    <div class="modal fade" id="deleteAllTourModal{{ $image->tour->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-warning">
                                                        <i class="icon-base bx bx-trash-alt me-1"></i> Delete All Images of Tour
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        <i class="icon-base bx bx-error-circle" style="font-size: 48px; color: #ffc107;"></i>
                                                    </div>
                                                    <p class="text-center mb-0">
                                                        Are you sure you want to delete <strong>ALL images</strong> of tour:
                                                    </p>
                                                    <p class="text-center fw-bold text-warning mt-2">
                                                        "{{ $image->tour->title ?? ($image->tour->name ?? 'N/A') }}"
                                                    </p>
                                                    <p class="text-center text-danger small mt-3">
                                                        <i class="icon-base bx bx-error-circle"></i> This will delete all images of this tour permanently!
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admin.image-tours.delete-all-tour', $image->tour->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-warning">
                                                            Yes, Delete All
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                 </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="icon-base bx bx-images fs-1 text-muted"></i>
                                    <p class="mt-2 mb-0">No images found</p>
                                    <a href="{{ route('admin.image-tours.create') }}"
                                        class="btn btn-sm btn-primary mt-2">
                                        <i class="icon-base bx bx-plus me-1"></i> Upload First Image
                                    </a>
                                 </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (isset($tourImages) && method_exists($tourImages, 'links'))
                <div class="card-footer">
                    {{ $tourImages->links() }}
                </div>
            @endif
        </div>

        <!-- Bulk Action Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Bulk Actions</h6>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">
                            <i class="icon-base bx bx-info-circle"></i>
                            Total images: <strong>{{ $tourImages->total() ?? count($tourImages) }}</strong>
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="button" class="btn btn-warning btn-sm" id="btnReorderImages">
                            <i class="icon-base bx bx-sort-alt-2"></i> Reorder All Images
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xóa TẤT CẢ images trong toàn bộ hệ thống -->
<div class="modal fade" id="deleteAllModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="icon-base bx bx-trash-alt me-1"></i> Delete ALL Images
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="icon-base bx bx-error-circle" style="font-size: 48px; color: #dc3545;"></i>
                </div>
                <p class="text-center fw-bold text-danger">
                    ⚠️ DANGER: This will delete ALL images in the system! ⚠️
                </p>
                <p class="text-center mb-0">Are you absolutely sure?</p>
                <div class="alert alert-warning mt-3">
                    <small>
                        <i class="icon-base bx bx-info-circle"></i>
                        This action will permanently delete <strong>{{ $tourImages->total() ?? count($tourImages) }}</strong> images
                        from the entire system.
                    </small>
                </div>
                <p class="text-center text-danger small mt-2">
                    <i class="icon-base bx bx-error-circle"></i> This action CANNOT be undone!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="icon-base bx bx-x me-1"></i> Cancel
                </button>
                <form action="{{ route('admin.image-tours.delete-all-system') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you REALLY sure? This cannot be undone!')">
                        <i class="icon-base bx bx-trash-alt me-1"></i> Yes, Delete All ({{ $tourImages->total() ?? count($tourImages) }} images)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Animation cho table rows
    document.querySelectorAll('.table tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });

    // Confirmation before delete
    document.querySelectorAll('form[action*="destroy"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this image? This action cannot be undone!')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection