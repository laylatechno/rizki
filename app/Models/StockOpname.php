<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockOpname extends Model
{
    protected $table = 'stock_opname';
    protected $guarded = [];

    /**
     * Relasi dengan tabel stock_opname_detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockOpnameDetails()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }



    public function products()
    {
        return $this->belongsToMany(Product::class, 'stock_opname_detail', 'stock_opname_id', 'product_id')
            ->withPivot('physical_stock', 'difference', 'description_detail');
    }
}
