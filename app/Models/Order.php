<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $guarded = [];
    // Di dalam model Order
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Di dalam model Order
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    // Pada model Order
    public function cash()
    {
        return $this->belongsTo(Cash::class);
    }



    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id');
    }
    
}
