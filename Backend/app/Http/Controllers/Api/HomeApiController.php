<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPosts;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourImage;

class HomeApiController extends Controller
{
    public function index()
    {
        $destinations = Destination::select('id', 'name', 'image')
            ->where('status', 'active')
            ->limit(7)
            ->get();

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

        $blogs = BlogPosts::with('category', 'author')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(9)
            ->get()
            ->map(fn($post) => [
                'id'       => $post->id,
                'title'    => $post->title,
                'slug'     => $post->slug,
                'thumbnail' => $post->thumbnail,
                'read_time' => $post->read_time,
                'category' => $post->category?->name,
                'author_avatar' => $post->author?->avatar ,
                'author'   => $post->author?->full_name ?? 'Admin',
            ]);
        

        return response()->json([
            'success' => true,
            'tours' => $tours,
            'categories' => $category,
            'destinations' => $destinations,
            'tourImages' => $tourImages,
            'blogs' => $blogs
        ]);
    }
}
