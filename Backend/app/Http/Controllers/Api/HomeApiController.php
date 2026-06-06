<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourImage;

class HomeApiController extends Controller
{
    public function index()
    {
        $destinations = Destination::select('id', 'name')
            ->where('status', 'active')
            ->get(); // ← Thêm get()

        $tours = Tour::all();

        $category = Category::select('id', 'name')
            ->where('status', 'active')
            ->get();

        $tourImages = TourImage::select('id', 'tour_id', 'image')
            ->whereIn('tour_id', $tours->pluck('id'))
            ->orderBy('id')
            ->get()
            ->groupBy('tour_id')
            ->map(function ($images) {
                return $images->first();
            })
            ->values();

        return response()->json([
            'success' => true,
            'tours' => $tours,
            'categories' => $category,
            'destinations' => $destinations,
            'tourImages' => $tourImages,
        ]);
    }
}
