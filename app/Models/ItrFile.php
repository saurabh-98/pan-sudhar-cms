<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItrFile extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table =
        'itr_files';

    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    protected $primaryKey =
        'id';

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    protected $perPage = 20;

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'user_id',

        'application_no',

        'name',

        'mobile',

        'email',

        'remarks',

        'admin_remarks',

        'aadhaar_front',

        'aadhaar_back',

        'pan_card',

        'charge',

        'payment_status',

        'status',

        'wallet_deducted',

        'wallet_deducted_at',

        'ip_address',

        'browser',

    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'status_badge',

        'applicant_name',

        'aadhaar_front_url',

        'aadhaar_back_url',

        'pan_card_url'

    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'charge' =>
            'decimal:2',

        'wallet_deducted' =>
            'boolean',

        'wallet_deducted_at' =>
            'datetime',

        'created_at' =>
            'datetime',

        'updated_at' =>
            'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPLICANT NAME
    |--------------------------------------------------------------------------
    */

    public function getApplicantNameAttribute(): string
    {
        return $this->name ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute(): string
    {
        return match (
            strtolower($this->status)
        ) {

            'approved' =>

                '<span class="badge bg-success">
                    Approved
                </span>',

            'rejected' =>

                '<span class="badge bg-danger">
                    Rejected
                </span>',

            'processing' =>

                '<span class="badge bg-warning text-dark">
                    Processing
                </span>',

            default =>

                '<span class="badge bg-secondary">
                    Pending
                </span>',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | AADHAAR FRONT URL
    |--------------------------------------------------------------------------
    */

    public function getAadhaarFrontUrlAttribute(): ?string
    {
        if (
            empty($this->aadhaar_front)
            ||
            !file_exists_custom(
                $this->aadhaar_front
            )
        ) {
            return null;
        }

        return file_url(
            $this->aadhaar_front
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AADHAAR BACK URL
    |--------------------------------------------------------------------------
    */

    public function getAadhaarBackUrlAttribute(): ?string
    {
        if (
            empty($this->aadhaar_back)
            ||
            !file_exists_custom(
                $this->aadhaar_back
            )
        ) {
            return null;
        }

        return file_url(
            $this->aadhaar_back
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAN CARD URL
    |--------------------------------------------------------------------------
    */

    public function getPanCardUrlAttribute(): ?string
    {
        if (
            empty($this->pan_card)
            ||
            !file_exists_custom(
                $this->pan_card
            )
        ) {
            return null;
        }

        return file_url(
            $this->pan_card
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FILE EXISTS
    |--------------------------------------------------------------------------
    */

    public function hasFile(
        string $column
    ): bool {

        if (
            empty(
                $this->{$column}
            )
        ) {
            return false;
        }

        return file_exists_custom(
            $this->{$column}
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where(
            'status',
            'Pending'
        );
    }

    public function scopeApproved($query)
    {
        return $query->where(
            'status',
            'Approved'
        );
    }

    public function scopeRejected($query)
    {
        return $query->where(
            'status',
            'Rejected'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function (
            $model
        ) {

            if (
                empty(
                    $model->application_no
                )
            ) {

                $model->application_no =

                    'ITR'

                    . date('Ymd')

                    . rand(
                        100000,
                        999999
                    );
            }

            if (
                empty(
                    $model->status
                )
            ) {

                $model->status =
                    'Pending';
            }

            if (
                empty(
                    $model->payment_status
                )
            ) {

                $model->payment_status =
                    'Pending';
            }
        });

        static::deleting(function (
            $model
        ) {

            foreach ([

                $model->aadhaar_front,

                $model->aadhaar_back,

                $model->pan_card

            ] as $file) {

                delete_uploaded_file(
                    $file
                );
            }
        });
    }
}