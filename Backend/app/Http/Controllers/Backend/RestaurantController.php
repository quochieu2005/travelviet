<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    // Hiển thị danh sách nhà hàng ở trang Admin
    public function index()
    {
        $restaurants = Restaurant::orderBy('id', 'desc')->get();
        return view('backend.restaurants.index', compact('restaurants'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        $destinations = Destination::where('status', 'active')->get();

        return view('backend.restaurants.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        // 1. Validate đầy đủ TẤT CẢ các trường gửi lên từ form để tránh lọc sót dữ liệu
        $request->validate([
            'title'    => 'required|string|max:255',
            'slug'     => 'nullable|string|max:255|unique:restaurants,slug',
            'destination_id' => 'required|exists:destinations,id',
            'price'    => 'required|numeric|min:0',
            'oldprice' => 'nullable|numeric|min:0',
            'rating'   => 'nullable|numeric|between:0,5',
            'reviews'  => 'nullable|integer|min:0',
            'tag'      => 'nullable|string|max:255',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status' => 'required|in:1,0',
        ]);

        // 2. Xử lý tạo Slug tự động và kiểm tra trùng lặp an toàn
        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Restaurant::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // 3. Xử lý upload ảnh lên Cloudinary
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $publicId = 'travelviet/restaurants/' . $slug;

            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );
            $imageUrl = $uploadResult['url'] ?? null;
        }

        // 4. Lưu vào Database dữ liệu đã sạch
        Restaurant::create([
            'title'    => $request->title,
            'slug'     => $slug,
            'destination_id' => $request->destination_id,
            'price'    => (float)$request->price,
            'oldprice' => $request->oldprice ? (float)$request->oldprice : null,
            'tag'      => $request->tag,
            'image'    => $imageUrl,
            'rating'   => (float)$request->get('rating', 0),
            'reviews'  => (int)$request->get('reviews', 0),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.restaurants.index')
            ->with('success', 'Restaurant created successfully.');
    }

    // Hiển thị form chỉnh sửa
    public function edit(Restaurant $restaurant)
    {
        $destinations = Destination::where('status', 'active')->get();

        return view('backend.restaurants.edit', compact(
            'restaurant',
            'destinations'
        ));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        // Kiểm tra validation bao gồm loại trừ ID hiện tại đối với trường Unique Slug
        $request->validate([
            'title'    => 'required|string|max:255',
            'slug'     => 'nullable|string|max:255|unique:restaurants,slug,' . $restaurant->id,
            'destination_id' => 'required|exists:destinations,id',
            'price'    => 'required|numeric|min:0',
            'oldprice' => 'nullable|numeric|min:0',
            'rating'   => 'nullable|numeric|between:0,5',
            'reviews'  => 'nullable|integer|min:0',
            'tag'      => 'nullable|string|max:255',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status' => 'required|in:1,0',
        ]);

        // Tạo slug mới dựa trên dữ liệu cập nhật
        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        // Vòng lặp loại trừ bản ghi hiện tại để không bị trùng lặp với bản ghi khác khi update
        while (Restaurant::where('slug', $slug)->where('id', '!=', $restaurant->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imageUrl = $restaurant->image;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $publicId = 'travelviet/restaurants/' . $slug;

            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );
            $imageUrl = $uploadResult['url'] ?? null;
        }

        $restaurant->update([
            'title'    => $request->title,
            'slug'     => $slug,
            'destination_id' => $request->destination_id,
            'price'    => (float)$request->price,
            'oldprice' => $request->oldprice ? (float)$request->oldprice : null,
            'tag'      => $request->tag,
            'image'    => $imageUrl,
            'rating'   => (float)$request->get('rating', $restaurant->rating),
            'reviews'  => (int)$request->get('reviews', $restaurant->reviews),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.restaurants.index')
            ->with('success', 'Restaurant updated successfully.');
    }

    // Xử lý xóa nhà hàng từ Admin
    public function destroy(Restaurant $restaurant)
    {
        try {
            $restaurant->delete();
            return redirect()
                ->route('admin.restaurants.index')
                ->with('success', 'Restaurant deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    // Thêm vào cuối class RestaurantController
    public function toggleStatus(Restaurant $restaurant)
    {
        try {
            $restaurant->status = ($restaurant->status === 'active') ? 'inactive' : 'active';


            $restaurant->save();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully.',
                    'status'  => $restaurant->status
                ]);
            }

            return redirect()->back()->with('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Update status failed: ' . $e->getMessage());
        }
    }
}
