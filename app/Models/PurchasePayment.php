<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'user_id',
        'payment_date',
        'amount',
        'payment_method',
        'notes',
    ];

    /**
     * Get the purchase that this payment belongs to.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the user who recorded this payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}