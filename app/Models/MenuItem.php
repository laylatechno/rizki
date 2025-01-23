<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'menu_items';
    protected $guarded = [];

    // Relasi ke MenuGroup
    public function group()
    {
        return $this->belongsTo(MenuGroup::class, 'menu_group_id');
    }

    // Relasi untuk sub-menu

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->where('status', 'Aktif')->orderBy('position');
    }


    // Relasi untuk menu induk
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }
}
