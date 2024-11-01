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

    protected $fillable = ['title', 'slug', 'short_description', 'image', 'content', 'created_at', 'updated_at'];

    protected $table = 'blog';

    protected $primaryKey = 'blog_id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }

    //Hàm func lấy tất cả blog
    public static function getAllBlog()
    {
        return self::all();
    }
}
