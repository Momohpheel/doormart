<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendors()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
}
