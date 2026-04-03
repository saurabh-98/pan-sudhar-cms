<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'table_id', // 🔥 IMPORTANT (added)

        'name',
        'email',
        'phone',

        'date',
        'time',

        'guests',
        'status',
        'notes'
    ];

    /* =========================
       CASTS (BEST PRACTICE)
    ========================= */
    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    /* =========================
       RELATIONS
    ========================= */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /* =========================
       STATUS HELPER
    ========================= */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /* =========================
       FORMATTED ATTRIBUTES
    ========================= */
    protected function formattedDate(): Attribute
    {
        return Attribute::get(fn () => $this->date?->format('d M Y'));
    }

    protected function formattedTime(): Attribute
    {
        return Attribute::get(fn () => $this->time?->format('h:i A'));
    }

    protected function statusBadge(): Attribute
    {
        return Attribute::get(function () {

            $map = [
                'pending'   => 'warning',
                'confirmed' => 'success',
                'cancelled' => 'danger',
            ];

            $class = $map[$this->status] ?? 'secondary';

            return "<span class='badge bg-{$class}'>" . ucfirst($this->status) . "</span>";
        });
    }
}