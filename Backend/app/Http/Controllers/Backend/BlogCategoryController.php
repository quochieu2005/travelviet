<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    // Danh sách danh mục
    public function index(Request $request)
    {
        $query = BlogCategories::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->paginate(10)->withQueryString();

        return view('backend.blog_categories.index', compact('categories'));
    }

    // Form tạo danh mục
    public function create()
    {
        return view('backend.blog_categories.create');
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:blog_categories,name',
            'slug'   => 'nullable|string|max:255|unique:blog_categories,slug',
            'status' => 'required|in:0,1'
        ]);

        // Xử lý slug: nếu có thì dùng, không thì tự tạo từ name
        if ($request->slug) {
            $slug = Str::slug($request->slug);
        } else {
            $slug = Str::slug($request->name);
        }

        // Kiểm tra unique slug
        $originalSlug = $slug;
        $count = 1;
        while (BlogCategories::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        BlogCategories::create([
            'name'   => $request->name,
            'slug'   => $slug,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    // Form chỉnh sửa
    public function edit(BlogCategories $blogCategory)
    {
        return view('backend.blog_categories.edit', compact('blogCategory'));
    }

    // Cập nhật
    public function update(Request $request, BlogCategories $blogCategory)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:blog_categories,name,' . $blogCategory->id,
            'slug'   => 'nullable|string|max:255|unique:blog_categories,slug,' . $blogCategory->id,
            'status' => 'required|in:0,1'
        ]);

        // Xử lý slug
        if ($request->slug) {
            $slug = Str::slug($request->slug);
        } else {
            $slug = Str::slug($request->name);
        }

        // Kiểm tra unique slug (trừ chính nó)
        $originalSlug = $slug;
        $count = 1;
        while (BlogCategories::where('slug', $slug)->where('id', '!=', $blogCategory->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $blogCategory->update([
            'name'   => $request->name,
            'slug'   => $slug,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    // Xóa
    public function destroy(BlogCategories $blogCategory)
    {
        // Kiểm tra có bài viết không
        if ($blogCategory->posts()->count() > 0) {
            return redirect()->route('admin.blog-categories.index')
                ->with('error', 'Không thể xóa danh mục đang có bài viết!');
        }

        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Xóa danh mục thành công!');
    }

    // Toggle status
    public function toggleStatus($slug)
    {
        $category = BlogCategories::where('slug', $slug)->firstOrFail();

        $category->status = $category->status == 1 ? 0 : 1;

        $category->save();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Cập nhật trạng thái thành công!');
    }
}
