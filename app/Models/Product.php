<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */

    protected $table = 'products';
    protected $guarded = [];

    // Relasi dengan Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi dengan Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function stockOpnames()
    {
        return $this->belongsToMany(StockOpname::class, 'stock_opname_detail', 'product_id', 'stock_opname_id')
            ->withPivot('physical_stock', 'difference', 'description_detail');
    }

    public function stockOpnameDetails()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }


    public function adjustmentDetails()
    {
        return $this->hasMany(AdjustmentDetail::class, 'product_id');
    }



    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id');
    }
}
