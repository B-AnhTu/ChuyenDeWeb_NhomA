<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;


class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';
    protected $fillable = ['title', 'slug', 'short_description', 'image', 'content', 'user_id', 'created_at', 'updated_at'];

    public static function getAllBlogsQuery()
    {
        return self::query()->orderBy('created_at', 'desc');
    }

    public static function searchFullText($searchTerm)
    {
        $keywords = explode(' ', $searchTerm);

        return self::where(function ($query) use ($keywords) {
            foreach ($keywords as $keyword) {
                $query->orWhere('title', 'LIKE', "%{$keyword}%")
                    ->orWhere('content', 'LIKE', "%{$keyword}%");
            }
        });
    }

    // Phương thức lấy bài viết theo slug
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    // Phương thức lấy các bài viết gần đây
    public static function getRecentPosts($limit = 5)
    {
        return self::orderBy('created_at', 'desc')->take($limit)->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }
}
