<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('destination')
            ->where('status', 1)
            ->get()
            ->map(fn($hotel) => $this->format($hotel));

        return response()->json(['hotels' => $hotels]);
    }

    public function show($slug)
    {
        $hotel = Hotel::with('destination')
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        return response()->json($this->format($hotel));
    }

    private function format($hotel)
    {
        return [
            'id'                => $hotel->id,
            'name'              => $hotel->name,
            'slug'              => $hotel->slug,
            'description'       => $hotel->description,
            'short_description' => $hotel->short_description,
            'price'             => $hotel->price,
            'rating'            => $hotel->rating,
            'reviews'           => $hotel->reviews,
            'thumbnail'         => $hotel->thumbnail,
            'facilities'        => $hotel->facilities ?? [],
            'location'          => $hotel->destination->name ?? '',
            'destination'       => [
                'id'   => $hotel->destination->id ?? null,
                'name' => $hotel->destination->name ?? '',
                'slug' => $hotel->destination->slug ?? '',
            ],
        ];
    }
}