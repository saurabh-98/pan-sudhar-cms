<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanFindHistory extends Model
{
    use HasFactory;

    /**
     * =========================================================
     * Table
     * =========================================================
     */

    protected $table = 'pan_find_histories';

    /**
     * =========================================================
     * Fillable
     * =========================================================
     */

    protected $fillable = [

        'user_id',

        'aadhaar_number',

        'amount',

        'status',

    ];

    /**
     * =========================================================
     * Casts
     * =========================================================
     */

    protected $casts = [

        'amount' => 'decimal:2',

    ];

    /**
     * =========================================================
     * Appends
     * =========================================================
     */

    protected $appends = [

        'status_badge',

        'masked_aadhaar',

    ];

    /**
     * =========================================================
     * Retailer
     * =========================================================
     */

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    /**
     * =========================================================
     * Mask Aadhaar
     * =========================================================
     */

    public function getMaskedAadhaarAttribute()
    {
        return 'XXXXXXXX' .

            substr(
                $this->aadhaar_number,
                -4
            );
    }

    /**
     * =========================================================
     * Status Badge
     * =========================================================
     */

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {

            'Completed' =>

                '<span class="badge bg-success">Completed</span>',

            'Rejected' =>

                '<span class="badge bg-danger">Rejected</span>',

            default =>

                '<span class="badge bg-warning text-dark">Pending</span>',

        };
    }
}