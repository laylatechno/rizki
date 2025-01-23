<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $guarded = [];

   
    // Pada model Blog.php
    public function blog_category()
    {
        return $this->belongsTo(BlogCategory::class);
    }
}
