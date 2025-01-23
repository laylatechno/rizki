<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $guarded = [];

        // Di dalam model OrderItem
        public function order()
        {
            return $this->belongsTo(Order::class);
        }
    
        // Di dalam model OrderItem
        public function product()
        {
            return $this->belongsTo(Product::class);
        }



        
    
}
