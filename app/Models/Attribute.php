<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'category_id',
        'name',
        'input_type',
    ];

    public function categories(): BelongsToMany
    {
        // This is the inverse of the relationship defined in the Category model
        return $this->belongsToMany(Category::class, 'attribute_category');
    }

    public function options()
    {
        return $this->hasMany(AttributeOption::class);
    }
}
