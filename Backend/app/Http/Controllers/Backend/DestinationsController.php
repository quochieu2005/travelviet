<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DestinationsController extends Controller
{
    public function index()
    {
        $destinations = Destination::whereIn('status', ['active', 'inactive'])->get();
        // dd($destinations);
        return view('backend.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('backend.destinations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:destinations,slug',
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

            $publicId = 'travelviet/destinations/' . $slug;

            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );

            $imageUrl = $uploadResult['url'] ?? null;
        }

        Destination::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'image'       => $imageUrl,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination created successfully.');
    }

    public function edit(Destination $destination)
    {
        return view('backend.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:destinations,slug,' . $destination->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        $imageUrl = $destination->image;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $publicId = 'travelviet/destinations/' . $slug;

            $uploadResult = app(\App\Services\CloudinaryService::class)->upload(
                $image->getRealPath(),
                $publicId
            );

            $imageUrl = $uploadResult['url'] ?? null;
        }

        $destination->update([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'image'       => $imageUrl,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination)
    {
        try {

            $destination->delete();

            return redirect()
                ->route('admin.destinations.index')
                ->with('success', 'Destination deleted successfully.');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Destination $destination)
    {
        $destination->status =
            $destination->status == 'active'
            ? 'inactive'
            : 'active';

        $destination->save();

        return redirect()
            ->back()
            ->with('success', 'Status updated successfully.');
    }
}
