<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_number',
        'quantity',
        'progress_percent',
        'start_date',
        'estimated_completion_date',
        'completed_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'estimated_completion_date' => 'date',
        'completed_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

