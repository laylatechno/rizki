<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;

    protected $table = 'profit_loss';
    protected $guarded = [];

    // Relasi ke tabel Cash
    public function cash()
    {
        return $this->belongsTo(Cash::class, 'cash_id');
    }

    // Relasi ke tabel TransactionCategory
    public function transactionCategory()
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }

     // Relasi dengan model Transaction
     public function transaction()
     {
         return $this->belongsTo(Transaction::class);
     }
 
     // Relasi dengan model Order
     public function order()
     {
         return $this->belongsTo(Order::class);
     }
 
     // Relasi dengan model Purchase
     public function purchase()
     {
         return $this->belongsTo(Purchase::class);
     }
}


