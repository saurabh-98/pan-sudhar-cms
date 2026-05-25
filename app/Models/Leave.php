<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Leave extends Model
{
    use HasFactory;

    /* =========================================================
     | TABLE
     *=========================================================*/
    protected $table = 'leaves';

    /* =========================================================
     | FILLABLE
     *=========================================================*/
    protected $fillable = [

        /* ================= EMPLOYEE ================= */
        'employee_id',

        /* ================= LEAVE ================= */
        'type',
        'from_date',
        'to_date',
        'leave_duration',
        'total_days',

        /* ================= DETAILS ================= */
        'reason',
        'document',

        /* ================= APPROVAL ================= */
        'status',
        'admin_remark',
        'approved_at',
        'approved_by',

        /* ================= EXTRA ================= */
        'is_paid',
    ];

    /* =========================================================
     | HIDDEN
     *=========================================================*/
    protected $hidden = [
        'updated_at',
    ];

    /* =========================================================
     | APPENDS
     *=========================================================*/
    protected $appends = [
        'duration',
        'formatted_from_date',
        'formatted_to_date',
        'status_badge',
    ];

    /* =========================================================
     | CASTS
     *=========================================================*/
    protected $casts = [

        'from_date'   => 'date',

        'to_date'     => 'date',

        'approved_at' => 'datetime',

        'is_paid'     => 'boolean',

        'total_days'  => 'decimal:1',
    ];

    /* =========================================================
     | RELATIONS
     *=========================================================*/

    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id'
        );
    }

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    /* =========================================================
     | ACCESSORS
     *=========================================================*/

    public function getDurationAttribute()
    {
        if ($this->leave_duration === 'Half Day') {
            return 0.5;
        }

        return Carbon::parse($this->from_date)
            ->diffInDays(
                Carbon::parse($this->to_date)
            ) + 1;
    }

    public function getFormattedFromDateAttribute()
    {
        return $this->from_date
            ? $this->from_date->format('d M Y')
            : null;
    }

    public function getFormattedToDateAttribute()
    {
        return $this->to_date
            ? $this->to_date->format('d M Y')
            : null;
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {

            'Approved' =>
                '<span class="badge bg-success">Approved</span>',

            'Rejected' =>
                '<span class="badge bg-danger">Rejected</span>',

            default =>
                '<span class="badge bg-warning text-dark">Pending</span>',
        };
    }

    /* =========================================================
     | SCOPES
     *=========================================================*/

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

    public function scopeToday($query)
    {
        return $query->whereDate(
            'created_at',
            today()
        );
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth(
                'created_at',
                now()->month
            )
            ->whereYear(
                'created_at',
                now()->year
            );
    }

    /* =========================================================
     | HELPERS
     *=========================================================*/

    public function isPending()
    {
        return $this->status === 'Pending';
    }

    public function isApproved()
    {
        return $this->status === 'Approved';
    }

    public function isRejected()
    {
        return $this->status === 'Rejected';
    }

    public function isHalfDay()
    {
        return $this->leave_duration === 'Half Day';
    }

    public function hasDocument()
    {
        return !empty($this->document);
    }

    public function documentUrl()
    {
        return $this->document
            ? asset('uploads/leaves/' . $this->document)
            : null;
    }
}