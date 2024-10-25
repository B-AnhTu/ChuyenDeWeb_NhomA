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

    protected $fillable = [
        'image',
        'title',
        'short_description',
        'content',
        'user_id',
        'created_at',
    ];

    protected $table = 'blog';

    protected $primaryKey = 'blog_id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function blogcomment(): HasOne{
    //     return $this->hasOne(BlogComment::class);
    // }

    //Hàm func lấy tất cả blog
    public static function getAllBlog(){
        return self::all();
    }
}
