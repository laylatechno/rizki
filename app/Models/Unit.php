<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';
    protected $guarded = [];

    // Relasi dengan Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
