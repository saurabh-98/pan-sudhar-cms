<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

class PanCorrectionApplication extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table =

        'pan_correction_applications';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'user_id',

        'first_name',
        'middle_name',
        'last_name',

        'old_pan_number',

        'gender',

        'pan_print_name',

        'father_first_name',
        'father_middle_name',
        'father_last_name',

        'mother_first_name',
        'mother_middle_name',
        'mother_last_name',

        'mobile_no',
        'email',

        'house_no',
        'village',
        'post_office',
        'area',

        'state',
        'district',
        'pincode',

        'identity_proof',
        'address_proof',
        'dob_proof',

        'dob',

        'aadhaar_no',
        'aadhaar_name',

        'signature_type',

        'photo',
        'signature',
        'aadhaar_card',

        'identity_proof_file',
        'address_proof_file',
        'dob_proof_file',

        'supporting_document',

        'assigned_to',
        'assigned_at',

        'status',
        'payment_status',

        'amount',

        'remarks'

    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'dob' => 'date',

        'assigned_at' => 'datetime',

        'amount' => 'decimal:2'

    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'full_name',

        'payment_badge',

        'status_badge'

    ];

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {

        return $this->belongsTo(

            User::class

        );

    }

    /*
    |--------------------------------------------------------------------------
    | RETAILER
    |--------------------------------------------------------------------------
    */

    public function retailer()
    {

        return $this->user?->retailer();

    }

    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    public function stateData(): BelongsTo
    {

        return $this->belongsTo(

            State::class,

            'state'

        );

    }

    /*
    |--------------------------------------------------------------------------
    | DISTRICT
    |--------------------------------------------------------------------------
    */

    public function districtData(): BelongsTo
    {

        return $this->belongsTo(

            District::class,

            'district'

        );

    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGNED USER
    |--------------------------------------------------------------------------
    */

    public function assignedUser(): BelongsTo
    {

        return $this->belongsTo(

            User::class,

            'assigned_to'

        );

    }

    /*
    |--------------------------------------------------------------------------
    | DOCUMENTS
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {

        return $this->hasMany(

            ServiceDocument::class,

            'service_id'

        )

        ->where(

            'service_type',

            'pan_correction'

        );

    }

    /*
    |--------------------------------------------------------------------------
    | FULL NAME
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {

        return trim(

            $this->first_name.' '.

            $this->middle_name.' '.

            $this->last_name

        );

    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT BADGE
    |--------------------------------------------------------------------------
    */

    public function getPaymentBadgeAttribute(): string
    {

        return match($this->payment_status){

            'Paid' =>

                '<span class="badge bg-success">
                    Paid
                </span>',

            'Failed' =>

                '<span class="badge bg-danger">
                    Failed
                </span>',

            default =>

                '<span class="badge bg-warning text-dark">
                    Pending
                </span>'

        };

    }

    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute(): string
    {

        return match($this->status){

            'Approved' =>

                '<span class="badge bg-primary">
                    Approved
                </span>',

            'Processing' =>

                '<span class="badge bg-info">
                    Processing
                </span>',

            'Completed' =>

                '<span class="badge bg-success">
                    Completed
                </span>',

            'Rejected' =>

                '<span class="badge bg-danger">
                    Rejected
                </span>',

            default =>

                '<span class="badge bg-warning text-dark">
                    Pending
                </span>'

        };

    }
}