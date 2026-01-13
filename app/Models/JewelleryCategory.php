<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JewelleryCategory extends Model
{
    protected $fillable = [
        'category_name',
        'status'
    ];

    public function products()
    {
        return $this->hasMany(JewelleryProduct::class, 'category_id');
    }
}
