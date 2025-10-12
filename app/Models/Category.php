<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'status'
        // REMOVED 'parent_id' from this array
    ];

    /**
     * The parents that belong to the category.
     * Renamed from parent() to parents()
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_parent', 'category_id', 'parent_id');
    }

    /**
     * The children that belong to the category.
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_parent', 'parent_id', 'category_id');
    }

    public function attributes(): BelongsToMany
{
    // Use withPivot to access the 'is_required' AND 'group_name' columns
    return $this->belongsToMany(Attribute::class, 'attribute_category')
                ->withPivot('is_required', 'group_name'); // 👈 ADD 'group_name' HERE
}

    // 👇 ADD THE FOLLOWING TWO METHODS

    /**
     * Get all parent category IDs in a flat array.
     */
    public function getAllParentIds(): array
    {
        $parentIds = [];
        $parent = $this->parent;
        while ($parent) {
            $parentIds[] = $parent->id;
            $parent = $parent->parent;
        }
        return $parentIds;
    }

    /**
     * Get all descendant category IDs in a flat array.
     */
    public function getAllChildIds(): array
    {
        $childIds = [];
        $children = $this->allChildren; // Uses the recursive relationship

        $traverse = function ($categories) use (&$traverse, &$childIds) {
            foreach ($categories as $category) {
                $childIds[] = $category->id;
                if ($category->allChildren->isNotEmpty()) {
                    $traverse($category->allChildren);
                }
            }
        };

        $traverse($children);
        return $childIds;
    }
}
