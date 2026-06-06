<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPosts extends Model
{
    use HasFactory;
    protected $table = 'blog_posts';

    protected $fillable = [
        'blog_category_id',
        'admin_id',
        'title',
        'slug',
        'status',
        'thumbnail',
        'thumbnail_id',
        'excerpt',
        'content',
        'read_time',
        'is_featured',
        'views',
        'trang_thai',
        'published_at',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategories::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
