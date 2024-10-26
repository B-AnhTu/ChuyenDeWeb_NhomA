<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'name',
        'verification_token',
        'verified_at',
        'is_active'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];
}
