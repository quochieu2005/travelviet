@extends('layouts.app')

@section('title', 'Tour Schedules - Admin TravelViet')

@section('content')

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="icon-base bx bx-calendar me-1"></i> Tour Schedules
                    </h5>
                    <a href="{{ route('admin.tour-schedules.create') }}" class="btn btn-primary">
                        <i class="icon-base bx bx-plus me-1"></i> Create Schedule
                    </a>
                </div>

                @include('components._message')

                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="18%">Tour</th>
                                <th width="11%">Khởi hành</th>
                                <th width="11%">Về</th>
                                <th width="10%">Chỗ trống</th>
                                <th width="11%">Giá NL override</th>
                                <th width="11%">Giá TE override</th>
                                <th width="13%">Ghi chú</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($tourSchedules as $index => $schedule)
                                <tr>
                                    <td>{{ $index + $tourSchedules->firstItem() }}</td>

                                    <td>
                                        <strong>{{ $schedule->tour->title ?? 'N/A' }}</strong>
                                    </td>

                                    <td>
                                        <span class="fw-medium">
                                            {{ $schedule->departure_date->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="fw-medium">
                                            {{ $schedule->return_date ? $schedule->return_date->format('d/m/Y') : '—' }}
                                        </span>
                                    </td>

                                    {{-- Chỗ trống: so với max_people của tour --}}
                                    <td>
                                        @php
                                            $maxPeople = $schedule->tour->max_people ?? 0;
                                            $slots = $schedule->available_slots;
                                            $percent = $maxPeople > 0 ? ($slots / $maxPeople) * 100 : 0;
                                            $badge =
                                                $percent > 50
                                                    ? 'bg-success'
                                                    : ($percent > 20
                                                        ? 'bg-warning'
                                                        : 'bg-danger');
                                        @endphp
                                        <span class="badge {{ $badge }}">
                                            {{ $slots }} / {{ $maxPeople > 0 ? $maxPeople : 'N/A' }}
                                        </span>
                                    </td>

                                    {{-- Giá override người lớn --}}
                                    <td>
                                        @if ($schedule->price_override)
                                            <span class="text-primary fw-bold">
                                                {{ number_format($schedule->price_override, 0, ',', '.') }}đ
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- Giá override trẻ em --}}
                                    <td>
                                        @if ($schedule->price_override_child)
                                            <span class="text-primary fw-bold">
                                                {{ number_format($schedule->price_override_child, 0, ',', '.') }}đ
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- Ghi chú --}}
                                    <td>
                                        @if ($schedule->note)
                                            <span class="badge bg-label-warning">{{ $schedule->note }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.tour-schedules.edit', $schedule->id) }}">
                                                    <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <button type="button" class="dropdown-item text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $schedule->id }}">
                                                    <i class="icon-base bx bx-trash me-1"></i> Delete
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Modal xóa --}}
                                        <div class="modal fade" id="deleteModal{{ $schedule->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-danger">
                                                            <i class="icon-base bx bx-trash me-1"></i> Confirm Delete
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <i class="bx bx-calendar-x mb-3"
                                                            style="font-size: 48px; color: #ffc107;"></i>
                                                        <p class="mb-1">Bạn có chắc muốn xóa lịch này không?</p>
                                                        <p class="text-muted small mb-0">
                                                            <strong>Tour:</strong>
                                                            {{ $schedule->tour->title ?? 'N/A' }}<br>
                                                            <strong>Khởi hành:</strong>
                                                            {{ $schedule->departure_date->format('d/m/Y') }}
                                                            @if ($schedule->note)
                                                                <br><strong>Ghi chú:</strong> {{ $schedule->note }}
                                                            @endif
                                                        </p>
                                                        <p class="text-danger small mt-2 mb-0">
                                                            <i class="bx bx-error-circle"></i> Hành động này không thể hoàn
                                                            tác!
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="bx bx-x me-1"></i> Hủy
                                                        </button>
                                                        <form
                                                            action="{{ route('admin.tour-schedules.destroy', $schedule->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bx bx-trash me-1"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="bx bx-calendar fs-1 text-muted"></i>
                                        <p class="mt-2 mb-0">Chưa có lịch khởi hành nào</p>
                                        <a href="{{ route('admin.tour-schedules.create') }}"
                                            class="btn btn-sm btn-primary mt-2">
                                            <i class="bx bx-plus me-1"></i> Tạo lịch đầu tiên
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (isset($tourSchedules) && method_exists($tourSchedules, 'links'))
                    <div class="card-footer">
                        {{ $tourSchedules->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
