<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * The products that belong to the flash sale.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'flash_sale_product')
                    ->withPivot('flash_price', 'quantity', 'sold')
                    ->withTimestamps();
    }
}