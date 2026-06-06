@extends('layouts.app')

@section('title', 'Edit Blog Post')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Edit Blog Post: {{ $blog->title }}</h5>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">
            <i class="icon-base bx bx-arrow-back me-1"></i> Back
        </a>
    </div>

    @include('components._message')

    <div class="card-body">
        <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <!-- Title -->
                    <div class="mb-3">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="title" 
                               id="title"
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $blog->title) }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" 
                               name="slug" 
                               id="slug"
                               class="form-control @error('slug') is-invalid @enderror" 
                               value="{{ old('slug', $blog->slug) }}">
                        <small class="form-text text-muted">Current slug: {{ $blog->slug }}</small>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-3">
                        <label>Excerpt (Short Description)</label>
                        <textarea name="excerpt" 
                                  rows="3" 
                                  class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $blog->excerpt) }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label>Content <span class="text-danger">*</span></label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="10" 
                                  class="form-control @error('content') is-invalid @enderror"
                                  required>{{ old('content', $blog->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Category -->
                    <div class="mb-3">
                        <label>Category <span class="text-danger">*</span></label>
                        <select name="blog_category_id" class="form-select @error('blog_category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('blog_category_id', $blog->blog_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('blog_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Read Time -->
                    <div class="mb-3">
                        <label>Read Time (minutes) <span class="text-danger">*</span></label>
                        <input type="number" 
                               name="read_time" 
                               class="form-control @error('read_time') is-invalid @enderror" 
                               value="{{ old('read_time', $blog->read_time) }}"
                               min="1"
                               required>
                        @error('read_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Featured -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="is_featured" 
                                   class="form-check-input" 
                                   value="1"
                                   id="is_featured"
                                   {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Post
                            </label>
                        </div>
                    </div>

                    <!-- Thumbnail -->
                    <div class="mb-3">
                        <label>Thumbnail</label>
                        <input type="file" 
                               name="thumbnail" 
                               id="thumbnail"
                               class="form-control @error('thumbnail') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg,image/webp">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if($blog->thumbnail)
                            <div id="currentThumbnail" class="mt-2">
                                <img src="{{ $blog->thumbnail }}" style="max-width: 100%; max-height: 150px; border-radius: 4px;">
                                <p class="small text-muted mt-1">Current thumbnail</p>
                            </div>
                        @endif
                        
                        <div id="thumbnailPreviewContainer" class="mt-2" style="display: none;">
                            <img id="thumbnailPreview" src="#" style="max-width: 100%; max-height: 150px; border-radius: 4px;">
                            <button type="button" id="removeThumbnail" class="btn btn-sm btn-danger mt-1">Remove new image</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-base bx bx-save me-1"></i> Update Post
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#content'))
        .catch(error => console.error(error));

    function stringToSlug(str) {
        str = str.toLowerCase();
        str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        str = str.replace(/[đĐ]/g, 'd');
        str = str.replace(/([^0-9a-z-\s])/g, '');
        str = str.replace(/\s+/g, '-');
        str = str.replace(/^-+|-+$/g, '');
        return str;
    }

    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    let isSlugManuallyEdited = false;

    titleInput.addEventListener('input', function() {
        if (!isSlugManuallyEdited && this.value) {
            slugInput.value = stringToSlug(this.value);
        }
    });

    slugInput.addEventListener('input', function() {
        isSlugManuallyEdited = true;
    });

    slugInput.addEventListener('blur', function() {
        if (this.value === '' && titleInput.value !== '') {
            slugInput.value = stringToSlug(titleInput.value);
            isSlugManuallyEdited = false;
        }
    });

    const thumbnailInput = document.getElementById('thumbnail');
    const previewContainer = document.getElementById('thumbnailPreviewContainer');
    const previewImage = document.getElementById('thumbnailPreview');
    const removeBtn = document.getElementById('removeThumbnail');

    thumbnailInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.src = event.target.result;
                previewContainer.style.display = 'block';
                const currentThumb = document.getElementById('currentThumbnail');
                if (currentThumb) currentThumb.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    removeBtn.addEventListener('click', function() {
        thumbnailInput.value = '';
        previewContainer.style.display = 'none';
        previewImage.src = '#';
        const currentThumb = document.getElementById('currentThumbnail');
        if (currentThumb) currentThumb.style.display = 'block';
    });
</script>
@endsection