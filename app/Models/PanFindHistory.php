<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

        'application_no',

        'aadhaar_number',

        'amount',

        'status',

        'assigned_to',

        'assigned_at',

        'remarks',

        'admin_remark',

        'payment_status',

        'wallet_deducted',

        'wallet_deducted_at',

        'extra_fields',

        'documents',

    ];

    /**
     * =========================================================
     * Casts
     * =========================================================
     */

    protected $casts = [

        'amount' => 'decimal:2',

        'assigned_at' => 'datetime',

        'wallet_deducted' => 'boolean',

        'wallet_deducted_at' => 'datetime',

        'extra_fields' => 'array',

        'documents' => 'array',

    ];

    /**
     * =========================================================
     * Appends
     * =========================================================
     */

    protected $appends = [

        'status_badge',

        'masked_aadhaar',

        'payment_badge',

        'service_name',

    ];

    /**
     * =========================================================
     * Boot
     * =========================================================
     */

    protected static function booted(): void
    {
        static::creating(function (PanFindHistory $model) {

            if (empty($model->application_no)) {

                $model->application_no =
                    'PANFIND' .
                    now()->format('ymd') .
                    strtoupper(Str::random(5));
            }

        });
    }

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
     | Assigned Executive
     * =========================================================
     */

    public function assignedUser()
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }

    /**
     * =========================================================
     * Executive Uploaded Documents (receipts, etc.)
     * =========================================================
     */

    public function serviceDocuments()
    {
        return $this->hasMany(
            ServiceDocument::class,
            'service_id'
        )->where(
            'service_type',
            'pan-find'
        );
    }

    /**
     * =========================================================
     * Get A Dynamic Field From The Submitted Application
     * =========================================================
     */

    public function getField(string $key, $default = null)
    {
        return data_get(
            $this->extra_fields,
            $key,
            $default
        );
    }

    /**
     * =========================================================
     * Service Name
     * =========================================================
     */

    public function getServiceNameAttribute()
    {
        return 'PAN Find Service';
    }

    /**
     * =========================================================
     * Mask Aadhaar
     * =========================================================
     */

    public function getMaskedAadhaarAttribute()
    {
        if (empty($this->aadhaar_number)) {
            return null;
        }

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

            'Completed', 'Approved' =>

                '<span class="badge bg-success">' .
                $this->status .
                '</span>',

            'Rejected' =>

                '<span class="badge bg-danger">Rejected</span>',

            default =>

                '<span class="badge bg-warning text-dark">Pending</span>',

        };
    }

    /**
     * =========================================================
     * Payment Badge
     * =========================================================
     */

    public function getPaymentBadgeAttribute()
    {
        return match ($this->payment_status) {

            'paid' =>

                '<span class="badge bg-success">Paid</span>',

            'refunded' =>

                '<span class="badge bg-secondary">Refunded</span>',

            default =>

                '<span class="badge bg-warning text-dark">Unpaid</span>',

        };
    }
}