<?php

namespace App\Models;

use App\Models\User;
use App\Models\ServiceDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AadhaarService extends Model
{
    use HasFactory;

    protected $table = 'aadhaar_services';

    protected $primaryKey = 'id';

    protected $perPage = 20;

    protected $fillable = [

        'user_id',

        'assigned_to',

        'application_no',

        'service_name',

        'service_slug',

        'form_data',

        'documents',

        'amount',

        'payment_status',

        'status',

        'wallet_deducted',

        'wallet_deducted_at',

        'ip_address',

        'browser',

        'admin_remark',
    ];

    protected $hidden = [

        'browser',

        'ip_address',
    ];

    protected $appends = [

        'dynamic_fields',

        'status_badge',

        'payment_badge',

        'service_display',

        'submitted_date',
    ];

    protected $casts = [

        'amount' => 'decimal:2',

        'wallet_deducted' => 'boolean',

        'wallet_deducted_at' => 'datetime',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'form_data' => 'array',

        'documents' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE UPLOADED DOCUMENTS
    |--------------------------------------------------------------------------
    */

    public function serviceDocuments(): HasMany
    {
        return $this->hasMany(
            ServiceDocument::class,
            'service_id'
        )->where(
            'service_type',
            'aadhaar'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute(): string
    {
        return match (
            strtolower($this->status ?? 'pending')
        ) {

            'approved' =>
                '<span class="badge bg-success">Approved</span>',

            'completed' =>
                '<span class="badge bg-success">Completed</span>',

            'rejected' =>
                '<span class="badge bg-danger">Rejected</span>',

            'processing' =>
                '<span class="badge bg-warning text-dark">Processing</span>',

            default =>
                '<span class="badge bg-secondary">Pending</span>',
        };
    }

    public function getPaymentBadgeAttribute(): string
    {
        return match (
            strtolower($this->payment_status ?? 'pending')
        ) {

            'paid' =>
                '<span class="badge bg-success">Paid</span>',

            'failed' =>
                '<span class="badge bg-danger">Failed</span>',

            default =>
                '<span class="badge bg-warning text-dark">Pending</span>',
        };
    }

    public function getServiceDisplayAttribute(): string
    {
        return $this->service_name ?? '';
    }

    public function getSubmittedDateAttribute(): string
    {
        return $this->created_at
            ? $this->created_at->format('d M Y h:i A')
            : '';
    }

    public function getDynamicFieldsAttribute(): array
    {
        return $this->form_data ?? [];
    }

    /*
    |--------------------------------------------------------------------------
    | DYNAMIC FIELD HELPERS
    |--------------------------------------------------------------------------
    */

    public function getField(
        string $key,
        mixed $default = null
    ): mixed {

        return data_get(
            $this->form_data ?? [],
            $key,
            $default
        );
    }

    public function getDocument(
        string $key,
        mixed $default = null
    ): mixed {

        return data_get(
            $this->documents ?? [],
            $key,
            $default
        );
    }

    public function hasFile(
        string $key
    ): bool {

        $path = $this->getDocument($key);

        if (!$path) {
            return false;
        }

        return file_exists_custom($path);
    }

    public function getDocumentUrl(
        string $key
    ): ?string {

        if (!$this->hasFile($key)) {
            return null;
        }

        return file_url(
            $this->getDocument($key)
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

    public function scopeCompleted($query)
    {
        return $query->where(
            'status',
            'Completed'
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

            if (empty($model->application_no)) {

                $model->application_no =
                    'AAD'
                    . date('YmdHis')
                    . rand(1000, 9999);
            }

            if (empty($model->status)) {

                $model->status = 'Pending';
            }

            if (empty($model->payment_status)) {

                $model->payment_status = 'Pending';
            }
        });

        static::deleting(function ($model) {

            foreach (
                $model->documents ?? []
                as $file
            ) {

                delete_uploaded_file($file);
            }
        });
    }
}