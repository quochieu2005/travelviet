@extends('layouts.app')

@section('title', 'Restaurants - Admin TravelViet')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Restaurant Table</h5>
            <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary">Create Restaurant</a>
        </div>

        @include('components._message')

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Old Price</th>
                        <th>Rating</th>
                        <th>Tag</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($restaurants as $key => $restaurant)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $restaurant->image ? $restaurant->image : asset('assets/img/avatars/1.png') }}"
                                        alt="{{ $restaurant->title }}" class="rounded"
                                        width="60" height="40" style="object-fit: cover;" />
                                </div>
                            </td>

                            <td>
                                <span class="fw-medium text-dark d-block">{{ $restaurant->title }}</span>
                                <small class="text-muted">{{ $restaurant->slug }}</small>
                            </td>

                            <td class="text-wrap" style="max-width: 200px;">
                                {{ $restaurant->location }}
                            </td>

                            <td>
                                <span class="fw-medium text-primary">{{ number_format($restaurant->price, 0, ',', '.') }} ₫</span>
                            </td>

                            <td>
                                @if ($restaurant->oldprice)
                                    <del class="text-muted small">{{ number_format($restaurant->oldprice, 0, ',', '.') }} ₫</del>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>

                            <td>
                                <span class="fw-medium text-warning">
                                    <i class="bx bxs-star me-1"></i>{{ $restaurant->rating }}
                                </span>
                                <small class="d-block text-muted">({{ $restaurant->reviews }} reviews)</small>
                            </td>

                            <td>
                                @if ($restaurant->tag)
                                    <span class="badge bg-label-info">{{ $restaurant->tag }}</span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>

                            <td>
                                <form action="{{ route('admin.restaurants.toggle-status', $restaurant->slug) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="border-0 bg-transparent p-0">
                                        @if ($restaurant->status == 'active' || $restaurant->status == 1)
                                            <span class="badge bg-label-success me-1">Active</span>
                                        @else
                                            <span class="badge bg-label-danger me-1">Inactive</span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.restaurants.edit', ['restaurant' => $restaurant->slug]) }}">
                                            <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $restaurant->id }}">
                                            <i class="icon-base bx bx-trash me-1"></i> Delete
                                        </button>
                                    </div>

                                    <div class="modal fade" id="deleteModal{{ $restaurant->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-wrap">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">
                                                        <i class="icon-base bx bx-trash me-1"></i> Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <div class="mb-3">
                                                        <i class="icon-base bx bx-question-mark fs-1" style="font-size: 48px; color: #ffc107;"></i>
                                                    </div>
                                                    <p class="mb-0">Are you sure you want to delete restaurant:</p>
                                                    <p class="fw-bold text-dark fs-5 mt-1">{{ $restaurant->title }}</p>
                                                    <p class="text-muted small">This action cannot be undone!</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="icon-base bx bx-x me-1"></i> Cancel
                                                    </button>
                                                    <form action="{{ route('admin.restaurants.destroy', $restaurant->slug) }}" method="POST" class="d-inline">
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
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted mb-2">No restaurants found.</div>
                                <a href="{{ route('admin.restaurants.create') }}" class="btn btn-sm btn-primary">
                                    Create your first restaurant
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (isset($restaurants) && method_exists($restaurants, 'links'))
        <div class="mt-3 d-flex justify-content-end">
            {{ $restaurants->links() }}
        </div>
    @endif
@endsection