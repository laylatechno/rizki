<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $table = 'cash';
    protected $guarded = [];

// Di model Transaction
public function profits()
{
    return $this->hasMany(Profit::class, 'transaction_id');
}


}
