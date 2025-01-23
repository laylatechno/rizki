<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';
    protected $guarded = [];

    // Di dalam model Purchase
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Di dalam model Purchase
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    
    // Pada model Purchase
    public function cash()
    {
        return $this->belongsTo(Cash::class);
    }
}
