<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
       'offer_price',
        'buying_price',      // 👈 Changed from 'price'
    'selling_price',     // 👈 Changed from 'sale_price'
        'status',
        'featured',
    ];

    /**
     * Get all of the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the stock record associated with the product.
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Get all of the attribute values for the product.
     */
    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function flashSales()
{
    return $this->belongsToMany(FlashSale::class, 'flash_sale_product')
                ->withPivot('flash_price', 'quantity', 'sold')
                ->withTimestamps();
}
}