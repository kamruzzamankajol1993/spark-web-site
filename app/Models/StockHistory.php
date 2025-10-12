<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        // 'size_id', // MODIFIED: Removed
        'previous_quantity',
        'new_quantity',
        'quantity_change',
        'type',
        'notes',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // MODIFIED: Removed size() relationship

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}