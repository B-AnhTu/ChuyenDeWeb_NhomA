<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = ['blog_id', 'user_id', 'content', 'status', 'name', 'email'];

    protected $table = 'blog_comment';

    protected $primaryKey = 'comment_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
}
