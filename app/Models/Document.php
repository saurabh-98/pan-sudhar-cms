<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [

        /* ================= DOCUMENT ================= */
        'title',
        'document_type',
        'file',
        'expiry_date',

        /* ================= RELATIONS ================= */
        'student_id',
        'employee_id',
        'category_id',

        /* ================= STATUS ================= */
        'status',
        'verified_by',
        'verified_at',
        'remarks',
    ];

    protected $casts = [

        'expiry_date' => 'date',

        'verified_at' => 'datetime',
    ];

    /* =========================================================
     | RELATIONSHIPS
     *=========================================================*/

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function category()
    {
        return $this->belongsTo(
            DocumentCategory::class,
            'category_id'
        );
    }

    public function verifier()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    /* =========================================================
     | ACCESSORS
     *=========================================================*/

    public function getFileUrlAttribute()
    {
        return asset(
            'uploads/documents/'.$this->file
        );
    }

    /* =========================================================
     | SCOPES
     *=========================================================*/

    public function scopeVerified($query)
    {
        return $query->where(
            'status',
            'Verified'
        );
    }

    public function scopePending($query)
    {
        return $query->where(
            'status',
            'Pending'
        );
    }
}