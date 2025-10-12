<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'vat_number',
        'status',
    ];

     public function purchases()
    {
        return $this->hasMany(Purchase::class)->orderBy('purchase_date', 'desc');
    }
}