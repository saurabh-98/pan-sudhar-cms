<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = [
        'class_id',
        'fee_type',
        'amount',
        'academic_year',

        // ✅ NEW FIELDS
        'is_mandatory',
        'fee_category',
        'frequency'
    ];

    protected $casts = [
        'amount' => 'float',
        'is_mandatory' => 'boolean'
    ];

    /* ================= RELATION ================= */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /* ================= SCOPES ================= */

    // Mandatory Fees (Admission, Tuition)
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', 1);
    }

    // Optional Fees (Hostel, Transport, Meal)
    public function scopeOptional($query)
    {
        return $query->where('is_mandatory', 0);
    }

    // Recurring Fees (Monthly)
    public function scopeRecurring($query)
    {
        return $query->where('fee_category', 'recurring');
    }

    // One-time Fees
    public function scopeOneTime($query)
    {
        return $query->where('fee_category', 'one_time');
    }

    /* ================= HELPERS ================= */

    public function isMandatory()
    {
        return $this->is_mandatory;
    }

    public function isRecurring()
    {
        return $this->fee_category === 'recurring';
    }

    public function getFormattedAmountAttribute()
    {
        return '₹ ' . number_format($this->amount, 2);
    }
}