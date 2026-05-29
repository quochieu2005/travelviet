@extends('layouts.app')

@section('title', 'Edit Image Tour - Admin TravelViet')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Image Tour</h5>
                    <a href="{{ route('admin.image-tours.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                </div>

                <div class="card-body">
                    <!-- Form chỉnh sửa 1 ảnh -->
                    <form action="{{ route('admin.image-tours.update', $tourImage->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Tour</label>
                            <div class="col-sm-10">
                                <select name="tour_id" class="form-control" required>
                                    @foreach ($tours as $tour)
                                        <option value="{{ $tour->id }}"
                                            {{ $tourImage->tour_id == $tour->id ? 'selected' : '' }}>
                                            {{ $tour->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Current Image</label>
                            <div class="col-sm-10">
                                <img src="{{ $tourImage->image }}"
                                    style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd; padding: 5px;">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Change Image (optional)</label>
                            <div class="col-sm-10">
                                <input type="file" name="image" class="form-control" accept="image/*" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Sort Order (Manual)</label>
                            <div class="col-sm-10">
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ $tourImage->sort_order }}" min="0" />
                                <small class="text-muted">Lower number = appears first. Leave empty for auto.</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Type</label>
                            <div class="col-sm-10">
                                <input type="text" name="type" class="form-control" value="{{ $tourImage->type }}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">Update Image</button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-5">

                    <!-- Drag & Drop sắp xếp tất cả ảnh -->
                    <h5>Drag & Drop to Reorder All Images</h5>
                    <p class="text-muted">Kéo thả để sắp xếp thứ tự ảnh (Tự động cập nhật)</p>

                    <div id="sortable-list" class="list-group">
                        @foreach ($allImages as $image)
                            <div class="list-group-item" data-id="{{ $image->id }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="drag-handle" style="cursor: move; padding: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="12" r="1"></circle>
                                            <circle cx="9" cy="5" r="1"></circle>
                                            <circle cx="9" cy="19" r="1"></circle>
                                            <circle cx="15" cy="12" r="1"></circle>
                                            <circle cx="15" cy="5" r="1"></circle>
                                            <circle cx="15" cy="19" r="1"></circle>
                                        </svg>
                                    </div>
                                    <img src="{{ $image->image }}"
                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                    <div class="flex-grow-1">
                                        <strong>Sort Order: <span
                                                class="order-number">{{ $image->sort_order }}</span></strong>
                                    </div>
                                    <span class="badge bg-primary">ID: {{ $image->id }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="save-order" class="btn btn-success mt-3">
                        <i class="icon-base bx bx-save me-1"></i> Save New Order
                    </button>
                    <button type="button" id="reset-order" class="btn btn-secondary mt-3">
                        <i class="icon-base bx bx-refresh me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        .ui-state-highlight {
            height: 80px;
            background-color: #f0f8ff;
            border: 2px dashed #007bff;
            margin-bottom: 5px;
        }

        .list-group-item {
            cursor: default;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .drag-handle {
            cursor: grab;
            color: #6c757d;
            user-select: none;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .sortable-placeholder {
            height: 80px;
            background-color: #e9ecef;
            border: 2px dashed #6c757d;
            margin-bottom: 5px;
        }
    </style>

    <script>
        $(document).ready(function() {
            if (typeof $.fn.sortable !== 'undefined') {
                console.log('jQuery UI Sortable is loaded!');

                $("#sortable-list").sortable({
                    handle: ".drag-handle",
                    placeholder: "ui-state-highlight",
                    opacity: 0.6,
                    cursor: "move",
                    tolerance: "pointer",
                    revert: 200,
                    start: function(event, ui) {
                        ui.placeholder.height(ui.item.height());
                    },
                    update: function(event, ui) {
                        // Cập nhật số thứ tự tạm thời khi kéo thả
                        $("#sortable-list .list-group-item").each(function(index) {
                            $(this).find('.order-number').text(index + 1);
                        });
                    }
                });

                $("#sortable-list").disableSelection();

            } else {
                console.error('jQuery UI Sortable is not loaded!');
                alert('Error: Sortable library not loaded. Please check your internet connection.');
            }

            $("#save-order").click(function() {
                var orders = [];
                $("#sortable-list .list-group-item").each(function() {
                    orders.push($(this).data('id'));
                });

                var $btn = $(this);
                var originalText = $btn.html();
                $btn.html('<i class="icon-base bx bx-loader-alt bx-spin me-1"></i> Saving...').prop(
                    'disabled', true);

                $.ajax({
                    url: "{{ route('admin.image-tours.update-sort-order') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        orders: orders
                    },
                    success: function(response) {
                        if (response.success) {
                            // Success message
                            alert('✓ ' + response.message);
                            location.reload();
                        } else {
                            alert('✗ Error updating order!');
                            $btn.html(originalText).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('✗ Something went wrong! Please try again.');
                        $btn.html(originalText).prop('disabled', false);
                    }
                });
            });

            $("#reset-order").click(function() {
                if (confirm('Reset to original order?')) {
                    location.reload();
                }
            });
        });

        if (typeof jQuery !== 'undefined' && typeof jQuery.ui === 'undefined') {
            console.warn('jQuery UI not loaded. Trying to load from CDN...');
            var script = document.createElement('script');
            script.src = 'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js';
            document.head.appendChild(script);
        }
    </script>
@endsection
