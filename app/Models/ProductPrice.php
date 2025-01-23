<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table = 'product_prices';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function customerCategory()
{
    return $this->belongsTo(CustomerCategory::class, 'customer_category_id');
}

}
