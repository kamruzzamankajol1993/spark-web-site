<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image', // Add image
        'start_date',
        'end_date',
        'status',
        'offerable_id',
        'offerable_type',
        'category_id', // Add category_id
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function offerable()
    {
        return $this->morphTo();
    }

    /**
     * Get the category context, used for brand-specific offers.
     */
    public function contextCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}