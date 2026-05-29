@extends('layouts.app')

@section('title', 'Tours - Admin TravelViet')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tour Table</h5>
            <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">Create Tour</a>
        </div>

        @include('components._message')

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Price Adult</th>
                        <th>Discount Adult</th>
                        <th>Price Child</th>
                        <th>Discount Child</th>
                        <th>Availability</th>
                        <th>Duration Days</th>
                        <th>Departure Location</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($tours as $tour)
                        <tr>
                            <!-- Destination -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $tour->destination->image ? $tour->destination->image : asset('assets/img/avatars/1.png') }}"
                                        alt="{{ $tour->destination->name ?? 'N/A' }}" class="rounded-circle me-3"
                                        width="40" height="40" style="object-fit: cover;" />
                                    <span class="fw-medium">{{ $tour->destination->name ?? 'N/A' }}</span>
                                </div>
                            </td>

                            <!-- Category -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $tour->category->image ? $tour->category->image : asset('assets/img/avatars/1.png') }}"
                                        alt="{{ $tour->category->name ?? 'N/A' }}" class="rounded-circle me-3"
                                        width="40" height="40" style="object-fit: cover;" />
                                    <span class="fw-medium">{{ $tour->category->name ?? 'N/A' }}</span>
                                </div>
                            </td>

                            <!-- Title -->
                            <td>
                                <span>{{ $tour->title }}</span>
                                <small class="d-block text-muted">
                                    {{ Str::limit($tour->short_description ?? $tour->description, 40) }}
                                </small>
                            </td>

                            <!-- Price Adult -->
                            <td>
                                <span class="fw-medium">{{ number_format($tour->price_adult, 0, ',', '.') }} ₫</span>
                            </td>

                            <!-- Discount Adult -->
                            <td>
                                @if ($tour->discount_price && $tour->discount_price < $tour->price_adult)
                                    <span class="fw-medium text-success">
                                        {{ number_format($tour->discount_price, 0, ',', '.') }} ₫
                                    </span>
                                    @php
                                        $calcByPercent = $tour->price_adult > 0
                                            ? (int) round($tour->price_adult * (1 - $tour->price_discount_percent / 100))
                                            : 0;
                                        $isPercent = $tour->price_discount_percent <= 100
                                            && $calcByPercent === (int) $tour->discount_price;
                                    @endphp
                                    @if ($isPercent)
                                        <small class="d-block text-muted">(-{{ $tour->price_discount_percent }}%)</small>
                                    @else
                                        <small class="d-block text-muted">
                                            (-{{ number_format($tour->price_discount_percent, 0, ',', '.') }} ₫)
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>

                            <!-- Price Child -->
                            <td>
                                @if ($tour->price_child)
                                    <span class="fw-medium">{{ number_format($tour->price_child, 0, ',', '.') }} ₫</span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>

                            <!-- Discount Child -->
                            <td>
                                @if ($tour->discount_price_child && $tour->discount_price_child < $tour->price_child)
                                    <span class="fw-medium text-success">
                                        {{ number_format($tour->discount_price_child, 0, ',', '.') }} ₫
                                    </span>
                                    @php
                                        $calcByPercentChild = $tour->price_child > 0
                                            ? (int) round($tour->price_child * (1 - $tour->price_child_discount_percent / 100))
                                            : 0;
                                        $isPercentChild = $tour->price_child_discount_percent <= 100
                                            && $calcByPercentChild === (int) $tour->discount_price_child;
                                    @endphp
                                    @if ($isPercentChild)
                                        <small class="d-block text-muted">(-{{ $tour->price_child_discount_percent }}%)</small>
                                    @else
                                        <small class="d-block text-muted">
                                            (-{{ number_format($tour->price_child_discount_percent, 0, ',', '.') }} ₫)
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>

                            <!-- Availability -->
                            <td>
                                <span class="badge bg-label-info">{{ $tour->availability }} slots</span>
                            </td>

                            <!-- Duration Days -->
                            <td>
                                <span class="badge bg-label-info">{{ $tour->duration_days ?? '?' }} days</span>
                            </td>

                            <!-- Departure Location -->
                            <td>{{ $tour->departure_location ?? 'N/A' }}</td>

                            <!-- Start Date -->
                            <td>
                                {{ $tour->start_date ? \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y') : 'N/A' }}
                            </td>

                            <!-- End Date -->
                            <td>
                                {{ $tour->end_date ? \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y') : 'N/A' }}
                            </td>

                            <!-- Views -->
                            <td>{{ number_format($tour->views ?? 0) }}</td>

                            <!-- Status -->
                            <td>
                                <form action="{{ route('admin.tours.toggle-status', $tour->slug) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="border-0 bg-transparent p-0">
                                        @if ($tour->status == 1)
                                            <span class="badge bg-label-success me-1">Active</span>
                                        @else
                                            <span class="badge bg-label-danger me-1">Inactive</span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ route('admin.tours.edit', ['tour' => $tour->slug]) }}">
                                            <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $tour->id }}">
                                            <i class="icon-base bx bx-trash me-1"></i> Delete
                                        </button>
                                    </div>

                                    <!-- Modal xác nhận xóa -->
                                    <div class="modal fade" id="deleteModal{{ $tour->id }}" tabindex="-1"
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
                                                        <strong>{{ $tour->title }}</strong>?
                                                    </p>
                                                    <p class="text-center text-muted small">This action cannot be undone!</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        <i class="icon-base bx bx-x me-1"></i> Cancel
                                                    </button>
                                                    <form action="{{ route('admin.tours.destroy', $tour->slug) }}"
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
                    @empty
                        <tr>
                            <td colspan="15" class="text-center py-4">
                                <div class="text-muted">No tours found.</div>
                                <a href="{{ route('admin.tours.create') }}" class="btn btn-sm btn-primary mt-2">
                                    Create your first tour
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if (isset($tours) && method_exists($tours, 'links'))
        <div class="mt-3 d-flex justify-content-end">
            {{ $tours->links() }}
        </div>
    @endif
@endsection