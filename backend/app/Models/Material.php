<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'unit',
        'stock',
        'low_stock_threshold',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_materials')
            ->withPivot('quantity_per_unit')
            ->withTimestamps();
    }
}

