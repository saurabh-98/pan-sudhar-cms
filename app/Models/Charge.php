<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    /**
     * Mass Assignable Fields
     */
    protected $fillable = [

        'name',

        'code',

        'type',

        'value',

        'description',

        'is_active'
    ];

    /**
     * Attribute Casting
     */
    protected $casts = [

        'value'     => 'decimal:2',

        'is_active' => 'boolean',
    ];

    /**
     * Get Charge Value By Code
     *
     * Example:
     * Charge::getValue('new_pan_apply');
     */
    public static function getValue(
        string $code
    ): float {

        return (float) self::where(
                'code',
                $code
            )
            ->where(
                'is_active',
                true
            )
            ->value(
                'value'
            ) ?? 0;
    }

    /**
     * Get Full Charge Record
     *
     * Example:
     * Charge::getCharge('file_itr');
     */
    public static function getCharge(
        string $code
    ): ?self {

        return self::where(
                'code',
                $code
            )
            ->where(
                'is_active',
                true
            )
            ->first();
    }

    /**
     * Scope Active Charges
     */
    public function scopeActive(
        $query
    ) {

        return $query->where(
            'is_active',
            true
        );
    }

    /**
     * Accessor For Status Text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active
            ? 'Active'
            : 'Inactive';
    }

    public function commissions()
    {
        return $this->hasMany(ChargeCommission::class);
    }
}