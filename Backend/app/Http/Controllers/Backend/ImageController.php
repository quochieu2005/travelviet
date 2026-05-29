<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function index()
    {
        $tourImages = TourImage::with('tour')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('backend.image_tours.index', compact('tourImages'));
    }

    public function create()
    {
        $tours = Tour::where('status', 1)->get();
        return view('backend.image_tours.create', compact('tours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'images' => 'max:10',
            'type' => 'nullable|string|max:50',
            'sort_order' => 'nullable|array',
            'sort_order.*' => 'nullable|integer|min:1',
        ]);

        $tour = Tour::findOrFail($request->tour_id);
        $slug = Str::slug($tour->title ?? 'tour');

        // Lấy sort_order từ request (nếu có)
        $sortOrders = $request->input('sort_order', []);

        // Lấy tất cả sort_order hiện có của tour này
        $existingOrders = TourImage::where('tour_id', $request->tour_id)->pluck('sort_order')->toArray();

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            $files = $request->file('images');

            foreach ($files as $index => $image) {
                $publicId = 'travelviet/tours/' . $slug . '_' . time() . '_' . $index;

                $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                    $image->getRealPath(),
                    $publicId
                );

                $imageUrl = $uploadResult['url'] ?? null;

                // Xác định sort_order
                if (isset($sortOrders[$index]) && !empty($sortOrders[$index])) {
                    $sortOrder = (int) $sortOrders[$index];

                    // Nếu sort_order đã tồn tại, đẩy các sort_order khác lên
                    if (in_array($sortOrder, $existingOrders)) {
                        // Tăng sort_order của các ảnh có sort_order >= vị trí mới lên 1
                        TourImage::where('tour_id', $request->tour_id)
                            ->where('sort_order', '>=', $sortOrder)
                            ->increment('sort_order');

                        // Cập nhật lại mảng existingOrders sau khi increment
                        $existingOrders = TourImage::where('tour_id', $request->tour_id)->pluck('sort_order')->toArray();
                    }
                } else {
                    // Nếu không có sort_order, lấy max + 1
                    $maxOrder = TourImage::where('tour_id', $request->tour_id)->max('sort_order') ?? 0;
                    $sortOrder = $maxOrder + 1;
                }

                $tourImage = TourImage::create([
                    'tour_id' => $request->tour_id,
                    'image' => $imageUrl,
                    'type' => $request->input('type', 'tour'),
                    'sort_order' => $sortOrder,
                ]);

                $uploadedImages[] = $tourImage;

                // Thêm sort_order mới vào mảng existingOrders để kiểm tra cho ảnh tiếp theo
                $existingOrders[] = $sortOrder;
            }
        }

        if (count($uploadedImages) > 0) {
            return redirect()
                ->route('admin.image-tours.index')
                ->with('success', 'Uploaded ' . count($uploadedImages) . ' images successfully with custom sort orders!');
        }

        return redirect()
            ->back()
            ->with('error', 'No images were uploaded!')
            ->withInput();
    }

    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:tour_images,id',
        ]);

        foreach ($request->orders as $index => $imageId) {
            TourImage::where('id', $imageId)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Sort order updated manually!']);
    }

    public function edit($id)
    {
        $tourImage = TourImage::findOrFail($id);
        $tours = Tour::where('status', 1)->get();

        $allImages = TourImage::where('tour_id', $tourImage->tour_id)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('backend.image_tours.edit', compact('tourImage', 'tours', 'allImages'));
    }

    public function update(Request $request, $id)
    {
        $tourImage = TourImage::findOrFail($id);
        $oldTourId = $tourImage->tour_id;
        $oldSortOrder = $tourImage->sort_order;

        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:1',
            'type' => 'nullable|string|max:50',
        ]);

        // Xử lý upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            $tour = Tour::findOrFail($request->tour_id);
            $slug = Str::slug($tour->title ?? 'tour');

            $image = $request->file('image');
            $publicId = 'travelviet/tours/' . $slug . '_' . time();

            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );

            $tourImage->image = $uploadResult['url'] ?? null;
        }

        // Cập nhật tour_id nếu thay đổi
        $tourImage->tour_id = $request->tour_id;
        $tourImage->type = $request->input('type', 'tour');

        // Xử lý sort_order nếu có thay đổi
        if ($request->has('sort_order') && $request->sort_order != $oldSortOrder) {
            $newSortOrder = $request->sort_order;

            // Nếu chuyển sang tour khác
            if ($oldTourId != $request->tour_id) {
                // Xử lý ở tour cũ: reorder các ảnh còn lại
                $this->reorderSortOrders($oldTourId);

                // Xử lý ở tour mới: chèn vào vị trí mong muốn
                $existingOrders = TourImage::where('tour_id', $request->tour_id)->pluck('sort_order')->toArray();

                if (in_array($newSortOrder, $existingOrders)) {
                    TourImage::where('tour_id', $request->tour_id)
                        ->where('sort_order', '>=', $newSortOrder)
                        ->increment('sort_order');
                }

                $tourImage->sort_order = $newSortOrder;
            } else {
                // Cùng tour, thay đổi vị trí
                if ($newSortOrder > $oldSortOrder) {
                    // Di chuyển xuống dưới: giảm các sort_order ở giữa
                    TourImage::where('tour_id', $request->tour_id)
                        ->where('sort_order', '>', $oldSortOrder)
                        ->where('sort_order', '<=', $newSortOrder)
                        ->decrement('sort_order');
                } elseif ($newSortOrder < $oldSortOrder) {
                    // Di chuyển lên trên: tăng các sort_order ở giữa
                    TourImage::where('tour_id', $request->tour_id)
                        ->where('sort_order', '>=', $newSortOrder)
                        ->where('sort_order', '<', $oldSortOrder)
                        ->increment('sort_order');
                }

                $tourImage->sort_order = $newSortOrder;
            }
        }

        $tourImage->save();

        // Đảm bảo sort_order đồng bộ
        $this->reorderSortOrders($tourImage->tour_id);

        if ($oldTourId != $request->tour_id) {
            $this->reorderSortOrders($oldTourId);
        }

        return redirect()
            ->route('admin.image-tours.index')
            ->with('success', 'Image updated successfully!');
    }

    // Hàm tự động sắp xếp lại sort_order cho đồng bộ
    private function reorderSortOrders($tourId)
    {
        $images = TourImage::where('tour_id', $tourId)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($images as $index => $image) {
            $image->sort_order = $index + 1;
            $image->save();
        }
    }

    public function destroy($id)
    {
        $tourImage = TourImage::findOrFail($id);
        $tourId = $tourImage->tour_id;

        // Xóa ảnh (có thể xóa trên Cloudinary)
        // app(\App\Services\CloudinaryService::class)->delete($tourImage->image);

        $tourImage->delete();

        // Tự động sắp xếp lại sort_order sau khi xóa
        $this->reorderSortOrders($tourId);

        return redirect()
            ->route('admin.image-tours.index')
            ->with('success', 'Image deleted successfully! Sort order updated automatically.');
    }

    /**
     * Delete all images of a specific tour
     */
    public function deleteAllImagesOfTour($tour_id)
    {
        $tour = Tour::findOrFail($tour_id);
        $count = TourImage::where('tour_id', $tour_id)->delete();

        if ($count == 0) {
            return redirect()
                ->route('admin.image-tours.index')
                ->with('error', 'No images found for this tour!');
        }

        return redirect()
            ->route('admin.image-tours.index')
            ->with('success', "Deleted {$count} images from tour '{$tour->title}' successfully!");
    }

    /**
     * Delete all images in the system
     */
    public function deleteAllSystem()
    {
        $count = TourImage::count();

        if ($count == 0) {
            return redirect()
                ->route('admin.image-tours.index')
                ->with('error', 'No images found in system!');
        }

        TourImage::truncate();

        return redirect()
            ->route('admin.image-tours.index')
            ->with('success', "Deleted all {$count} images from system successfully!");
    }
}
