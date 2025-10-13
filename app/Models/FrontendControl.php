<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'header_category_ids',
        'sidebar_category_ids',
    ];

    protected $casts = [
        'header_category_ids' => 'array',
        'sidebar_category_ids' => 'array',
    ];
}