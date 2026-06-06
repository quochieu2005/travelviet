<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategories;
use App\Models\BlogPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    // Hiển thị danh sách bài viết
    public function index(Request $request)
    {
        $query = BlogPosts::with('category', 'author')->latest();

        if ($request->filled('category')) {
            $query->where('blog_category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts      = $query->paginate(10)->withQueryString();
        $categories = BlogCategories::all();

        return view('backend.blogs.index', compact('posts', 'categories'));
    }

    // Form tạo bài viết mới
    public function create()
    {
        $categories = BlogCategories::all();
        return view('backend.blogs.create', compact('categories'));
    }

    // Lưu bài viết mới
    public function store(Request $request)
    {
        $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt'          => 'nullable|string',
            'content'          => 'required|string',
            'read_time'        => 'required|integer|min:1',
            'status'           => 'required|in:draft,published',
            'thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Xử lý slug
        $slug = $request->slug 
            ? Str::slug($request->slug) 
            : Str::slug($request->title);
        
        // Kiểm tra slug unique
        $originalSlug = $slug;
        $count = 1;
        while (BlogPosts::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $thumbnailUrl = null;
        $thumbnailId = null;

        // Upload thumbnail lên Cloudinary
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $publicId = 'travelviet/blog/' . $slug;
            
            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );
            
            $thumbnailUrl = $uploadResult['url'] ?? null;
            $thumbnailId = $uploadResult['public_id'] ?? null;
        }

        $data = [
            'blog_category_id' => $request->blog_category_id,
            'admin_id'         => auth('admin')->id(),
            'title'            => $request->title,
            'slug'             => $slug,
            'excerpt'          => $request->excerpt,
            'content'          => $request->content,
            'read_time'        => $request->read_time,
            'status'           => $request->status,
            'is_featured'      => $request->boolean('is_featured'),
            'thumbnail'        => $thumbnailUrl,
            'thumbnail_id'     => $thumbnailId,
        ];

        // Xử lý published_at
        if ($request->status === 'published') {
            $data['published_at'] = now();
        }

        BlogPosts::create($data);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Tạo bài viết thành công!');
    }

    // Xem chi tiết bài viết
    public function show(BlogPosts $blog)
    {
        $blog->load('category', 'author');
        return view('backend.blogs.show', compact('blog'));
    }

    // Form chỉnh sửa bài viết
    public function edit(BlogPosts $blog)
    {
        $categories = BlogCategories::all();
        return view('backend.blogs.edit', compact('blog', 'categories'));
    }

    // Cập nhật bài viết
    public function update(Request $request, BlogPosts $blog)
    {
        $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'excerpt'          => 'nullable|string',
            'content'          => 'required|string',
            'read_time'        => 'required|integer|min:1',
            'status'           => 'required|in:draft,published',
            'thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Xử lý slug
        $slug = $request->slug 
            ? Str::slug($request->slug) 
            : Str::slug($request->title);
        
        // Kiểm tra slug unique (trừ chính nó)
        $originalSlug = $slug;
        $count = 1;
        while (BlogPosts::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $data = [
            'blog_category_id' => $request->blog_category_id,
            'title'            => $request->title,
            'slug'             => $slug,
            'excerpt'          => $request->excerpt,
            'content'          => $request->content,
            'read_time'        => $request->read_time,
            'status'           => $request->status,
            'is_featured'      => $request->boolean('is_featured'),
        ];

        // Cập nhật published_at nếu chuyển sang published lần đầu
        if ($request->status === 'published' && !$blog->published_at) {
            $data['published_at'] = now();
        }

        // Upload thumbnail mới (xóa ảnh cũ trên Cloudinary trước)
        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ nếu có
            if ($blog->thumbnail_id) {
                try {
                    app(\App\Services\CloudinaryService::class)->delete($blog->thumbnail_id);
                } catch (\Exception $e) {
                    // Log error nếu cần
                }
            }

            $image = $request->file('thumbnail');
            $publicId = 'travelviet/blog/' . $slug;
            
            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );
            
            $data['thumbnail'] = $uploadResult['url'] ?? null;
            $data['thumbnail_id'] = $uploadResult['public_id'] ?? null;
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    // Xoá bài viết
    public function destroy(BlogPosts $blog)
    {
        try {
            // Xóa ảnh trên Cloudinary nếu có
            if ($blog->thumbnail_id) {
                app(\App\Services\CloudinaryService::class)->delete($blog->thumbnail_id);
            }

            $blog->delete();

            return redirect()->route('admin.blogs.index')
                ->with('success', 'Xoá bài viết thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Xóa thất bại: ' . $e->getMessage());
        }
    }

    // Toggle featured status
    public function toggleFeatured(BlogPosts $blog)
    {
        $blog->is_featured = !$blog->is_featured;
        $blog->save();

        return redirect()->back()
            ->with('success', 'Cập nhật trạng thái nổi bật thành công!');
    }

    // Toggle status (published/draft)
    public function toggleStatus(BlogPosts $blog)
    {
        $blog->status = $blog->status == 'published' ? 'draft' : 'published';
        
        if ($blog->status == 'published' && !$blog->published_at) {
            $blog->published_at = now();
        }
        
        $blog->save();

        return redirect()->back()
            ->with('success', 'Cập nhật trạng thái bài viết thành công!');
    }
}