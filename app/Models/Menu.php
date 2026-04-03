<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'category_id',
        'description',
        'specifications', // ✅ IMPORTANT (added)
        'is_available'
    ];

    /**
     * Relationships
     */

    // belongs to category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // order items (many orders)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // offers applied on this menu
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}