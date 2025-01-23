<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpnameDetail extends Model
{
    protected $table = 'stock_opname_detail';
    protected $guarded = [];

    /**
     * Relasi dengan tabel stock_opname
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class, 'stock_opname_id');
    }

    /**
     * Relasi dengan tabel produk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
