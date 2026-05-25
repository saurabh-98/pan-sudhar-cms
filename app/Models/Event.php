<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [

        /* ================= EVENT ================= */
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',

        /* ================= CATEGORY ================= */
        'category_id',

        /* ================= IMAGE ================= */
        'banner',

        /* ================= STATUS ================= */
        'is_holiday',
        'status',

        /* ================= USER ================= */
        'created_by',
    ];

    protected $casts = [

        'event_date' => 'date',

        'is_holiday' => 'boolean',
    ];

    protected $appends = [
        'banner_url',
        'formatted_event_date'
    ];

    /* =========================================================
     | RELATIONSHIPS
     *=========================================================*/

    public function category()
    {
        return $this->belongsTo(
            EventCategory::class,
            'category_id'
        );
    }

    public function getFormattedEventDateAttribute()
    {
        return $this->event_date
            ? $this->event_date->format('d M Y')
            : '-';
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /* =========================================================
     | ACCESSORS
     *=========================================================*/

    public function getBannerUrlAttribute()
    {
        if (
            $this->banner &&
            file_exists(
                public_path('uploads/events/' . $this->banner)
            )
        ) {
            return asset(
                'uploads/events/' . $this->banner
            );
        }

        return asset('images/no-image.png');
    }


    /* =========================================================
     | SCOPES
     *=========================================================*/

    public function scopeUpcoming($query)
    {
        return $query->where(
            'status',
            'Upcoming'
        );
    }

    public function scopeHoliday($query)
    {
        return $query->where(
            'is_holiday',
            true
        );
    }
}