<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPosts;
use App\Models\BlogCategories;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    // Lấy danh sách bài viết với phân trang
    public function getPosts(Request $request)
    {
        $query = BlogPosts::with('category', 'author')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        // Filter theo category
        if ($request->filled('category_id')) {
            $query->where('blog_category_id', $request->category_id);
        }

        // Filter theo category slug
        if ($request->filled('category_slug')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category_slug);
            });
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->get('per_page', 9);
        $posts = $query->paginate($perPage);

        // Transform dữ liệu cho phù hợp với React
        $posts->getCollection()->transform(function($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'thumbnail' => $post->thumbnail,
                'read_time' => $post->read_time,
                'published_at' => $post->published_at?->format('Y-m-d'),
                'category' => [
                    'id' => $post->category?->id,
                    'name' => $post->category?->name,
                    'slug' => $post->category?->slug,
                ],
                'author' => [
                    'author_avatar' => $post->author?->avatar ,
                    'name'   => $post->author?->full_name ?? 'Admin',
                    'email' => $post->author?->email,
                ],
                'is_featured' => (bool)$post->is_featured,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Lấy danh sách bài viết thành công'
        ]);
    }

    // Lấy bài viết nổi bật
    public function getFeaturedPosts(Request $request)
    {
        $limit = $request->get('limit', 3);
        
        $posts = BlogPosts::with('category', 'author')
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get()
            ->transform(function($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'thumbnail' => $post->thumbnail,
                    'read_time' => $post->read_time,
                    'published_at' => $post->published_at?->format('Y-m-d'),
                    'category' => [
                        'name' => $post->category?->name,
                        'slug' => $post->category?->slug,
                    ],
                    'author' => [
                        'author_avatar' => $post->author?->avatar ,
                        'name'   => $post->author?->full_name ?? 'Admin',
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Lấy bài viết nổi bật thành công'
        ]);
    }

    // Lấy chi tiết bài viết theo slug
    public function getPostBySlug($slug)
    {
        $post = BlogPosts::with('category', 'author')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết'
            ], 404);
        }

        // Tăng view count (nếu có)
        // $post->increment('views');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'thumbnail' => $post->thumbnail,
                'read_time' => $post->read_time,
                'published_at' => $post->published_at?->format('d/m/Y'),
                'category' => [
                    'id' => $post->category?->id,
                    'name' => $post->category?->name,
                    'slug' => $post->category?->slug,
                ],
                'author' => [
                    'author_avatar' => $post->author?->avatar ,
                    'name'   => $post->author?->full_name ?? 'Admin',
                    'email' => $post->author?->email,
                ],
                'is_featured' => (bool)$post->is_featured,
            ],
            'message' => 'Lấy chi tiết bài viết thành công'
        ]);
    }

    // Lấy danh sách categories
    public function getCategories(Request $request)
    {
        $categories = BlogCategories::where('status', 1)
            ->orderBy('name')
            ->get()
            ->transform(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'post_count' => $category->posts()->where('status', 'published')->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Lấy danh sách danh mục thành công'
        ]);
    }

    // Lấy bài viết liên quan
    public function getRelatedPosts($postId, Request $request)
    {
        $limit = $request->get('limit', 3);
        
        $currentPost = BlogPosts::find($postId);
        
        if (!$currentPost) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết'
            ], 404);
        }

        $posts = BlogPosts::with('category', 'author')
            ->where('status', 'published')
            ->where('id', '!=', $postId)
            ->where('blog_category_id', $currentPost->blog_category_id)
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get()
            ->transform(function($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'thumbnail' => $post->thumbnail,
                    'read_time' => $post->read_time,
                    'published_at' => $post->published_at?->format('Y-m-d'),
                    'category' => [
                        'name' => $post->category?->name,
                        'slug' => $post->category?->slug,
                    ],
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Lấy bài viết liên quan thành công'
        ]);
    }
}