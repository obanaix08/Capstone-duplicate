<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'item_type',
        'item_id',
        'quantity',
        'reason',
    ];

    public function movable()
    {
        return $this->morphTo();
    }
}

