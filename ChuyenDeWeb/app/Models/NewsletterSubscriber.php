<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'is_active'];

    /**
     * Tạo subscriber mới
     *
     * @param array $data
     * @return self
     */
    public static function createSubscriber(array $data)
    {
        return self::create([
            'name' => $data['name'] ?? null,
            'email' => $data['email'],
            'is_active' => true,
        ]);
    }
}
