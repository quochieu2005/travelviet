@extends('layouts.app')

@section('title', 'Blog Posts Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Blog Posts</h5>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
            <i class="icon-base bx bx-plus-circle me-1"></i> Create Post
        </a>
    </div>

    @include('components._message')

    <!-- Filter -->
    <div class="card-body border-bottom">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-info">
                    <i class="icon-base bx bx-filter-alt me-1"></i> Filter
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
                    <i class="icon-base bx bx-reset me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th width="50">ID</th>
                    <th width="80">Thumbnail</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Read Time</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Published Date</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>
                        @if($post->thumbnail)
                            <img src="{{ $post->thumbnail }}" width="60" height="60" style="object-fit: cover; border-radius: 4px;">
                        @else
                            <div class="bg-secondary rounded" style="width: 60px; height: 60px;"></div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $post->title }}</strong>
                        <br>
                        <small class="text-muted">{{ $post->slug }}</small>
                    </td>
                    <td>{{ $post->category->name ?? 'Uncategorized' }}</td>
                    <td><span class="badge bg-label-info">{{ $post->read_time }} min</span></td>
                    <td>{{ number_format($post->views ?? 0) }}</td>
                    <td>
                        @if($post->status == 'published')
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-warning">Draft</span>
                        @endif
                    </td>
                    <td>
                        @if($post->is_featured)
                            <span class="badge bg-danger">Featured</span>
                        @endif
                    </td>
                    <td>
                        @if($post->published_at)
                            {{ $post->published_at->format('d/m/Y') }}
                        @else
                            <span class="text-muted">Not published</span>
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.blogs.show', $post) }}">
                                        <i class="icon-base bx bx-show me-1"></i> View
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.blogs.edit', $post) }}">
                                        <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $post->id }}">
                                        <i class="icon-base bx bx-trash me-1"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $post->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete <strong>"{{ $post->title }}"</strong>?</p>
                                        <p class="text-muted small">This action cannot be undone!</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('admin.blogs.destroy', $post) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <i class="icon-base bx bx-news fs-1 text-muted"></i>
                        <p class="mt-2">No blog posts found!</p>
                        <a href="{{ route('admin.blogs.create') }}" class="btn btn-sm btn-primary">Create first post</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $posts->links() }}
    </div>
</div>
@endsection