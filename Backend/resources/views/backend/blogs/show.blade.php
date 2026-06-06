@extends('layouts.app')

@section('title', $blog->title)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Blog Post Details</h5>
        <div>
            <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-warning btn-sm">
                <i class="icon-base bx bx-edit-alt me-1"></i> Edit
            </a>
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">
                <i class="icon-base bx bx-arrow-back me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h2 class="mb-3">{{ $blog->title }}</h2>
                <p class="text-muted">
                    <i class="icon-base bx bx-category"></i> {{ $blog->category->name ?? 'Uncategorized' }} &nbsp;|&nbsp;
                    <i class="icon-base bx bx-time"></i> {{ $blog->read_time }} min read &nbsp;|&nbsp;
                    <i class="icon-base bx bx-calendar"></i> Published: {{ $blog->published_at ? $blog->published_at->format('d/m/Y H:i') : 'Not published yet' }} &nbsp;|&nbsp;
                    <i class="icon-base bx bx-show"></i> {{ number_format($blog->views ?? 0) }} views
                </p>
                
                @if($blog->excerpt)
                    <div class="alert alert-info">
                        <strong>Excerpt:</strong><br>
                        {{ $blog->excerpt }}
                    </div>
                @endif

                <hr>
                
                <div class="content">
                    {!! $blog->content !!}
                </div>
            </div>

            <div class="col-md-4">
                <!-- Thumbnail -->
                @if($blog->thumbnail)
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Thumbnail</strong>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $blog->thumbnail }}" class="img-fluid rounded" alt="{{ $blog->title }}">
                        </div>
                    </div>
                @endif

                <!-- Info Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>Information</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>ID:</th>
                                <td>{{ $blog->id }}</td>
                            </tr>
                            <tr>
                                <th>Slug:</th>
                                <td><code>{{ $blog->slug }}</code></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($blog->status == 'published')
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Featured:</th>
                                <td>
                                    @if($blog->is_featured)
                                        <span class="badge bg-danger">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Author:</th>
                                <td>{{ $blog->author->name ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $blog->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>{{ $blog->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection