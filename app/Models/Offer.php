<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'title',
        'type',
        'value',
        'code',
        'min_order',
        'max_discount',
        'menu_id',
        'category_id',
        'expires_at',
        'is_active',
        'image' // 
    ];

    /**
     * Relationships
     */

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 🔥 Accessor for image URL (BEST PRACTICE)
     */
    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset($this->image) 
            : asset('images/no-image.png'); // fallback
    }

    /**
     * 🔥 Check if offer is valid
     */
    public function isValid($cartTotal)
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return false;
        }

        if ($this->min_order && $cartTotal < $this->min_order) {
            return false;
        }

        return true;
    }

    /**
     * 🔥 Calculate discount
     */
    public function calculateDiscount($total)
    {
        if ($this->type === 'percent') {

            $discount = ($total * $this->value) / 100;

            if ($this->max_discount) {
                return min($discount, $this->max_discount);
            }

            return $discount;
        }

        if ($this->type === 'fixed') {
            return $this->value;
        }

        return 0;
    }
}