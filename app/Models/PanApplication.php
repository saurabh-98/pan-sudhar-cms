<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;

class PanApplication extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table =
        'pan_applications';

    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id';

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    protected $perPage = 20;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'user_id',

        'assigned_to',

        'application_no',
        'pan_type',

        'first_name',
        'middle_name',
        'last_name',

        'dob',
        'gender',

        'father_first_name',
        'father_middle_name',
        'father_last_name',

        'mother_first_name',
        'mother_middle_name',
        'mother_last_name',

        'pan_print_name',

        'mobile_no',
        'email',

        'aadhaar_no',
        'aadhaar_name',

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

        'signature_type',

        'photo',
        'signature',

        'aadhaar_card',

        'dob_proof_file',

        'supporting_document',

        'amount',
        'payment_status',

        'status',

        'wallet_deducted',
        'wallet_deducted_at',

        'ip_address',
        'browser',

        'admin_remark'

    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */

    protected $hidden = [

        'aadhaar_no',
        'browser',
        'ip_address'

    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'dob' =>
            'date:Y-m-d',

        'amount' =>
            'decimal:2',

        'wallet_deducted' =>
            'boolean',

        'wallet_deducted_at' =>
            'datetime',

        'created_at' =>
            'datetime:d M Y h:i A',

        'updated_at' =>
            'datetime:d M Y h:i A'

    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'status_badge',

        'payment_badge',

        'full_address',

        'masked_aadhaar',

        'applicant_name',

        'father_full_name',

        'mother_full_name',

        'assigned_user_name',

        'photo_url',

        'signature_url',

        'aadhaar_card_url',

        'dob_proof_file_url',

        'supporting_document_url'

    ];

    /*
    |--------------------------------------------------------------------------
    | USER RELATIONSHIP
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
    | STATUS BADGE
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {

            'Approved' =>

                '<span class="badge bg-success">
                    Approved
                </span>',

            'Rejected' =>

                '<span class="badge bg-danger">
                    Rejected
                </span>',

            'Processing' =>

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
    | PAYMENT BADGE
    |--------------------------------------------------------------------------
    */

    public function getPaymentBadgeAttribute(): string
    {
        return match ($this->payment_status) {

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
                </span>',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | FULL ADDRESS
    |--------------------------------------------------------------------------
    */

    public function getFullAddressAttribute(): string
    {
        return collect([

            $this->house_no,
            $this->village,
            $this->post_office,
            $this->area,
            $this->district,
            $this->state,
            $this->pincode

        ])
        ->filter()
        ->implode(', ');
    }

    /*
    |--------------------------------------------------------------------------
    | MASKED AADHAAR
    |--------------------------------------------------------------------------
    */

    public function getMaskedAadhaarAttribute(): string
    {
        return 'XXXXXXXX'

            . substr(
                $this->aadhaar_no,
                -4
            );
    }

    /*
    |--------------------------------------------------------------------------
    | APPLICANT NAME
    |--------------------------------------------------------------------------
    */

    public function getApplicantNameAttribute(): string
    {
        return trim(

            $this->first_name . ' ' .

            ($this->middle_name ?? '') . ' ' .

            $this->last_name

        );
    }

    /*
    |--------------------------------------------------------------------------
    | FATHER FULL NAME
    |--------------------------------------------------------------------------
    */

    public function getFatherFullNameAttribute(): string
    {
        return trim(

            $this->father_first_name . ' ' .

            ($this->father_middle_name ?? '') . ' ' .

            $this->father_last_name

        );
    }

    /*
    |--------------------------------------------------------------------------
    | MOTHER FULL NAME
    |--------------------------------------------------------------------------
    */

    public function getMotherFullNameAttribute(): string
    {
        return trim(

            $this->mother_first_name . ' ' .

            ($this->mother_middle_name ?? '') . ' ' .

            $this->mother_last_name

        );
    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGNED USER NAME
    |--------------------------------------------------------------------------
    */

    public function getAssignedUserNameAttribute(): ?string
    {
        return $this->assignedUser?->name;
    }

    /*
    |--------------------------------------------------------------------------
    | PHOTO URL
    |--------------------------------------------------------------------------
    */

    public function getPhotoUrlAttribute(): ?string
    {
        return file_url(
            $this->photo
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIGNATURE URL
    |--------------------------------------------------------------------------
    */

    public function getSignatureUrlAttribute(): ?string
    {
        return file_url(
            $this->signature
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AADHAAR CARD URL
    |--------------------------------------------------------------------------
    */

    public function getAadhaarCardUrlAttribute(): ?string
    {
        return file_url(
            $this->aadhaar_card
        );
    }

   
    /*
    |--------------------------------------------------------------------------
    | DOB PROOF FILE URL
    |--------------------------------------------------------------------------
    */

    public function getDobProofFileUrlAttribute(): ?string
    {
        return file_url(
            $this->dob_proof_file
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPORTING DOCUMENT URL
    |--------------------------------------------------------------------------
    */

    public function getSupportingDocumentUrlAttribute(): ?string
    {
        return file_url(
            $this->supporting_document
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
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

    public function scopeProcessing($query)
    {
        return $query->where(
            'status',
            'Processing'
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

        static::creating(function ($model) {

            /*
            |--------------------------------------------------------------------------
            | APPLICATION NUMBER
            |--------------------------------------------------------------------------
            */

            if (empty($model->application_no))
            {
                $model->application_no =

                    'PAN'

                    . date('Ymd')

                    . rand(100000,999999);
            }

            /*
            |--------------------------------------------------------------------------
            | DEFAULT STATUS
            |--------------------------------------------------------------------------
            */

            if(empty($model->status))
            {
                $model->status =
                    'Pending';
            }

            /*
            |--------------------------------------------------------------------------
            | DEFAULT PAYMENT
            |--------------------------------------------------------------------------
            */

            if(empty($model->payment_status))
            {
                $model->payment_status =
                    'Pending';
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | STATE RELATION
    |--------------------------------------------------------------------------
    */

    public function stateData()
    {
        return $this->belongsTo(
            State::class,
            'state'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISTRICT RELATION
    |--------------------------------------------------------------------------
    */

    public function districtData()
    {
        return $this->belongsTo(
            District::class,
            'district'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DOCUMENTS
    |--------------------------------------------------------------------------
    */

    public function documents()
    {
        return $this->hasMany(

            ServiceDocument::class,

            'service_id'

        )->where(

            'service_type',

            'pan'

        );
    }
}