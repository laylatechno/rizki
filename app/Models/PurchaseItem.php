<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items';
    protected $guarded = [];

    // Di dalam model PurchaseItem
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    // Di dalam model PurchaseItem
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    
}
