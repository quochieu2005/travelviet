<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourImage;
use Illuminate\Http\Request;

class TourApiController extends Controller
{
    public function index(Request $request)
    {
        $tours = Tour::where('status', '1')->get();

        $tourImages = TourImage::select('id', 'tour_id', 'image')
            ->whereIn('tour_id', $tours->pluck('id'))
            ->orderBy('sort_order')
            ->get()
            ->groupBy('tour_id')
            ->map(fn($images) => $images->first()->image);

        // Gắn image vào từng tour
        $toursWithImage = $tours->map(function ($tour) use ($tourImages) {
            $tour->thumbnail = $tourImages[$tour->id] ?? null;
            return $tour;
        });

        $destinations = Destination::select('id', 'name')->get()
            ->pluck('name', 'id'); // key = id, value = name


        return response()->json([
            'success' => true,
            'Tours'   => $toursWithImage,
            'destinations' => $destinations,
        ]);
    }

    public function show($slug)
    {

        $tour = Tour::where('slug', $slug)->where('status', '1')->firstOrFail();

        $images = TourImage::where('tour_id', $tour->id)
            ->orderBy('sort_order')
            ->pluck('image');

        return response()->json([
            'success' => true,
            'tour'    => $tour,
            'images'  => $images,
        ]);
    }
}
