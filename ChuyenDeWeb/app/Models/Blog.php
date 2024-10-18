<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'content'
    ];

    protected $table = 'blog';

    protected $primaryKey = 'blog_id';

    // public function user(): BelongsTo{
    //     return $this->belongsTo(User::class);
    // }

    // public function blogcomment(): HasOne{
    //     return $this->hasOne(BlogComment::class);
    // }
}
