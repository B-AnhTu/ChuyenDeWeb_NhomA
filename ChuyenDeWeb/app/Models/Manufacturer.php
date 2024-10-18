<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_name'
    ];

    protected $table = 'manufacturer';

    protected $primaryKey = 'manufacturer_id';
}
