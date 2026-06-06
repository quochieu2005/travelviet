<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategories extends Model
{
    use HasFactory;
    protected $table = 'blog_categories';

    protected $fillable = [ 'name' , 'slug' , 'status'];

    public function posts()
    {
        return $this->hasMany(BlogPosts::class, 'blog_category_id');
    }

    public function getRouteKeyName()
{
    return 'slug';
}
}
