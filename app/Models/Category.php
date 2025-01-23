<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $guarded = [];

     // Relasi dengan Product
     public function products()
     {
         return $this->hasMany(Product::class);
     }
}
