<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'retailer_id',
        'admin_id',
        'status',
        'last_message',
        'last_message_at',
        'closed_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'closed_at'       => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function unreadMessagesForAdmin()
    {
        return $this->messages()
            ->where('sender_type', 'retailer')
            ->where('is_read', false);
    }

    public function unreadMessagesForRetailer()
    {
        return $this->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false);
    }
}