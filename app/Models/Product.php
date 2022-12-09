<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_name','product_price','product_description'
    ];

    public function images(){
        return $this->hasMany(Image::class);
    }

    public function getData()
    {
        return static::orderBy('created_at','desc')->get();
    }
}
