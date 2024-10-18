<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content'
    ];

    protected $table = 'blog_comment';

    protected $primaryKey = 'comment_id';


}
