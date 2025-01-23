<?php

 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustmentDetail extends Model
{
    use HasFactory; // Menambahkan trait HasFactory
    protected $table = 'adjustment_details';
    protected $guarded = [];

    // Relasi ke Adjustment (Many-to-One)
    public function adjustment()
    {
        return $this->belongsTo(Adjustment::class, 'adjustment_id');
    }

    // Relasi ke Product (Many-to-One)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
