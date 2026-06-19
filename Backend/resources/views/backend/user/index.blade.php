@extends('layouts.app')

@section('title', 'Users - Admin TravelViet')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Management</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="icon-base bx bx-user-plus me-1"></i> Add User
            </a>
        </div>

        @include('components._message')

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Balance</th>
                        <th>Total Spent</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <img src="{{ $user->avatar ? (filter_var($user->avatar, FILTER_VALIDATE_URL) ? $user->avatar : asset('storage/' . $user->avatar)) : asset('assets/img/avatars/default-avatar.jpg') }}"
                                    alt="{{ $user->full_name }}"
                                    class="rounded-circle"
                                    width="40" height="40"
                                    style="object-fit: cover;" />
                            </td>
                            <td><span class="fw-medium">{{ $user->username ?? '—' }}</span></td>
                            <td>{{ $user->full_name }}</td>
                            <td>
                                {{ $user->email }}
                                @if($user->email_verified_at)
                                    <i class="icon-base bx bx-check-circle text-success" title="Verified"></i>
                                @else
                                    <i class="icon-base bx bx-x-circle text-danger" title="Not verified"></i>
                                @endif
                            </td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td><span class="fw-bold text-success">{{ number_format($user->balance, 0, ',', '.') }} ₫</span></td>
                            <td><span class="fw-medium text-primary">{{ number_format($user->total_spent, 0, ',', '.') }} ₫</span></td>
                            <td>
                                @php
                                    $roleColors = ['admin' => 'danger', 'tour_guide' => 'warning', 'customer' => 'info'];
                                    $roleLabels = ['admin' => 'Admin', 'tour_guide' => 'Tour Guide', 'customer' => 'Customer'];
                                    $color = $roleColors[$user->role] ?? 'secondary';
                                @endphp
                                <span class="badge bg-label-{{ $color }}">{{ $roleLabels[$user->role] ?? ucfirst($user->role) }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="border-0 bg-transparent p-0">
                                        @if ($user->is_active)
                                            <span class="badge bg-label-success">Active</span>
                                        @else
                                            <span class="badge bg-label-danger">Inactive</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') : '—' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.users.show', $user->id) }}">
                                            <i class="icon-base bx bx-show me-1"></i> View
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                            <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        @if($user->role !== 'admin' || auth()->id() !== $user->id)
                                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                                <i class="icon-base bx bx-trash me-1"></i> Delete
                                            </button>
                                        @else
                                            <button type="button" class="dropdown-item text-muted" disabled>
                                                <i class="icon-base bx bx-trash me-1"></i> Cannot delete self
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">
                                                        <i class="icon-base bx bx-trash me-1"></i> Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        <i class="icon-base bx bx-question-mark fs-1" style="font-size: 48px; color: #ffc107;"></i>
                                                    </div>
                                                    <p class="text-center mb-0">Are you sure you want to delete <strong>{{ $user->full_name }}</strong>?</p>
                                                    <p class="text-center text-muted small">Email: {{ $user->email }}</p>
                                                    <p class="text-center text-muted small">This action cannot be undone!</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="icon-base bx bx-x me-1"></i> Cancel
                                                    </button>
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
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
                            <td colspan="12" class="text-center py-4">
                                <div class="text-muted">No users found.</div>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="icon-base bx bx-user-plus me-1"></i> Add your first user
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (isset($users) && method_exists($users, 'links'))
        <div class="mt-3 d-flex justify-content-end">
            {{ $users->links() }}
        </div>
    @endif
@endsection