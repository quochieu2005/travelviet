<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::whereIn('status', ['active', 'inactive'])->get();
        // dd($categories);
        return view('backend.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        $imageUrl = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $publicId = 'travelviet/categories/' . $slug;

            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );

            $imageUrl = $uploadResult['url'] ?? null;
        }

        Category::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'image'       => $imageUrl,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $categories)
    {
        return view('backend.categories.edit', compact('categories'));
    }

    public function update(Request $request, Category $categories)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $categories->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        $imageUrl = $categories->image;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $publicId = 'travelviet/categories/' . $slug;

            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );

            $imageUrl = $uploadResult['url'] ?? null;
        }

        $categories->update([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'image'       => $imageUrl,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $categories)
    {
        try {

            $categories->delete();

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Category $categories)
    {
        $categories->status =
            $categories->status == 'active'
            ? 'inactive'
            : 'active';

        $categories->save();

        return redirect()
            ->back()
            ->with('success', 'Status updated successfully.');
    }
}
