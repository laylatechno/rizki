<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    use HasFactory; // Menambahkan trait HasFactory
    protected $table = 'adjustments';
    protected $guarded = [];

    // Relasi ke AdjustmentDetail (One-to-Many)
    // public function details()
    // {
    //     return $this->hasMany(AdjustmentDetail::class, 'adjustment_id');
    // }

    // Relasi ke User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Model Adjustment
    public function details()
    {
        return $this->hasMany(AdjustmentDetail::class);
    }

    // Model AdjustmentDetail
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
