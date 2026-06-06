<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public static $availableIcons = [
        'bi bi-wifi'         => 'Free Wi-Fi',
        'bi bi-cup-hot'      => 'Breakfast Included',
        'bi bi-bus-front'    => 'Airport Shuttle',
        'bi bi-droplet-half' => 'Swimming Pool',
        'bi bi-dumbbell'     => 'Fitness Center',
        'bi bi-paw'          => 'Pet-Friendly',
        'bi bi-clock'        => '24-Hour Front Desk',
        'bi bi-tshirt'       => 'Laundry Service',
        'bi bi-people'       => 'Shared Lounge',
        'bi bi-bicycle'      => 'Bicycle Rental',
        'bi bi-briefcase'    => 'Business Center',
        'bi bi-building'     => 'Rooftop Bar',
        'bi bi-palette'      => 'Art Gallery',
        'bi bi-person'       => 'Concierge Service',
        'bi bi-spa'          => 'Spa Services',
    ];

    public function index()
    {
        $hotels = Hotel::with('destination')->paginate(10);
        return view('backend.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $icons        = self::$availableIcons;
        $destinations = Destination::where('status', 'active')->get();
        return view('backend.hotels.create', compact('icons', 'destinations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:hotels,slug',
            'destination_id'    => 'required|exists:destinations,id',
            'price'             => 'required|string',
            'rating'            => 'nullable|numeric|min:0|max:5',
            'reviews'           => 'nullable|integer',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status'            => 'nullable|in:0,1,on,off',   // Khuyến nghị
        ]);

        $price = (int) str_replace('.', '', $request->price);

        $slug = $request->filled('slug') 
            ? Str::slug($request->slug) 
            : Str::slug($request->name);
        
        $originalSlug = $slug;
        $count = 1;
        while (Hotel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $thumbnail = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $publicId = 'travelviet/hotels/' . $slug;
            
            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );
            
            $thumbnail = $uploadResult['url'] ?? null;
        }

        $facilities = collect($request->facilities ?? [])
            ->filter(fn($f) => !empty($f['icon']))
            ->map(fn($f) => [
                'name' => self::$availableIcons[$f['icon']] ?? $f['icon'],
                'icon' => $f['icon'],
            ])->values()->toArray();

        Hotel::create([
            'name'              => $request->name,
            'slug'              => $slug,
            'destination_id'    => $request->destination_id,
            'price'             => $price,
            'rating'            => $request->rating,
            'reviews'           => $request->reviews,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'thumbnail'         => $thumbnail,
            'thumbnail_id'      => null,
            'facilities'        => $facilities,
            'status'            => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel created successfully!');
    }

    public function edit(Hotel $hotel)
    {
        $icons        = self::$availableIcons;
        $destinations = Destination::where('status', 'active')->get();
        return view('backend.hotels.edit', compact('hotel', 'icons', 'destinations'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:hotels,slug,' . $hotel->id,
            'destination_id'    => 'required|exists:destinations,id',
            'price'             => 'required|string',
            'rating'            => 'nullable|numeric|min:0|max:5',
            'reviews'           => 'nullable|integer',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status'            => 'nullable|in:0,1,on,off',   // Khuyến nghị
        ]);

        $price = (int) str_replace('.', '', $request->price);

        $slug = $request->filled('slug') 
            ? Str::slug($request->slug) 
            : Str::slug($request->name);

        if ($slug !== $hotel->slug) {
            $originalSlug = $slug;
            $count = 1;
            while (Hotel::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        }

        $thumbnail = $hotel->thumbnail;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $publicId = 'travelviet/hotels/' . $slug;
            
            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );
            
            $thumbnail = $uploadResult['url'] ?? $thumbnail;
        }

        $facilities = collect($request->facilities ?? [])
            ->filter(fn($f) => !empty($f['icon']))
            ->map(fn($f) => [
                'name' => self::$availableIcons[$f['icon']] ?? $f['icon'],
                'icon' => $f['icon'],
            ])->values()->toArray();

        $hotel->update([
            'name'              => $request->name,
            'slug'              => $slug,
            'destination_id'    => $request->destination_id,
            'price'             => $price,
            'rating'            => $request->rating,
            'reviews'           => $request->reviews,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'thumbnail'         => $thumbnail,
            'thumbnail_id'      => null,
            'facilities'        => $facilities,
            'status'            => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel updated successfully!');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel deleted!');
    }
}