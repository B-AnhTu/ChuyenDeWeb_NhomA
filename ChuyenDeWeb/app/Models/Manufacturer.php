<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_name',
        'image',
        'slug'
    ];

    protected $table = 'manufacturer';
    protected $primaryKey = 'manufacturer_id'; // Specify the correct primary key

    // protected $primaryKey = 'manufacturer_id';


    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'manufacturer_id');
    // }

    // public static function getAllManufacturer(){
    //     return self::all();
    // }
}
