<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * =========================================================
     * SPATIE GUARD
     * =========================================================
     */

    protected $guard_name = 'web';

    /**
     * =========================================================
     * FILLABLE
     * =========================================================
     */

    protected $fillable = [

        'name',

        'email',

        'password',

        'image',

        'status',

        'first_login',

        'registration_no',
    ];

    /**
     * =========================================================
     * HIDDEN
     * =========================================================
     */

    protected $hidden = [

        'password',

        'remember_token',
    ];

    /**
     * =========================================================
     * CASTS
     * =========================================================
     */

    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',

            'status' => 'boolean',

            'first_login' => 'boolean',
        ];
    }

    /**
     * =========================================================
     * APPENDS
     * =========================================================
     */

    protected $appends = [

        'role_name',

        'role_badge',
    ];

    /**
     * =========================================================
     * ACCESSORS
     * =========================================================
     */

    /*
    |--------------------------------------------------------------------------
    | ROLE NAME
    |--------------------------------------------------------------------------
    */

    public function getRoleNameAttribute()
    {
        return $this->getRoleNames()
            ->first() ?? 'No Role';
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE BADGE
    |--------------------------------------------------------------------------
    */

    public function getRoleBadgeAttribute()
    {
        $role = strtolower(
            $this->role_name
        );

        if (str_contains($role, 'admin')) {

            return 'danger';
        }

        if (str_contains($role, 'teacher')) {

            return 'success';
        }

        if (str_contains($role, 'principal')) {

            return 'dark';
        }

        if (str_contains($role, 'accountant')) {

            return 'warning';
        }

        if (str_contains($role, 'hr')) {

            return 'info';
        }

        if (str_contains($role, 'reception')) {

            return 'secondary';
        }

        if (str_contains($role, 'transport')) {

            return 'primary';
        }

        if (str_contains($role, 'library')) {

            return 'success';
        }

        if (str_contains($role, 'student')) {

            return 'info';
        }

        if (str_contains($role, 'parent')) {

            return 'warning';
        }

        return 'primary';
    }

    /**
     * =========================================================
     * MESSAGES
     * =========================================================
     */

    public function sentMessages()
    {
        return $this->hasMany(

            Message::class,

            'sender_id'
        );
    }

    public function receivedMessages()
    {
        return $this->hasMany(

            Message::class,

            'receiver_id'
        );
    }

    /**
     * =========================================================
     * SCHOOL RELATIONS
     * =========================================================
     */

    public function student()
    {
        return $this->hasOne(
            Student::class
        );
    }

    public function teacher()
    {
        return $this->hasOne(
            Teacher::class
        );
    }

    /**
     * =========================================================
     * ORDERS
     * =========================================================
     */

    public function orders()
    {
        return $this->hasMany(
            Order::class
        );
    }

    /**
     * =========================================================
     * LIBRARY
     * =========================================================
     */

    public function bookIssues()
    {
        return $this->hasMany(
            BookIssue::class
        );
    }

    /**
     * =========================================================
     * LEAVES
     * =========================================================
     */

    public function leaves()
    {
        return $this->hasMany(
            Leave::class
        );
    }

    /**
     * =========================================================
     * ROLE HELPERS
     * =========================================================
     */

    public function isAdmin(): bool
    {
        return $this->hasRole(
            'admin'
        );
    }

    public function isPrincipal(): bool
    {
        return $this->hasRole(
            'principal'
        );
    }

    public function isVicePrincipal(): bool
    {
        return $this->hasRole(
            'vice principal'
        );
    }

    public function isTeacher(): bool
    {
        return $this->hasRole(
            'teacher'
        );
    }

    public function isAccountant(): bool
    {
        return $this->hasRole(
            'accountant'
        );
    }

    public function isReceptionist(): bool
    {
        return $this->hasRole(
            'receptionist'
        );
    }

    public function isHrManager(): bool
    {
        return $this->hasRole(
            'hr manager'
        );
    }

    public function isTransportManager(): bool
    {
        return $this->hasRole(
            'transport manager'
        );
    }

    public function isLibraryManager(): bool
    {
        return $this->hasRole(
            'library manager'
        );
    }

    public function isStudent(): bool
    {
        return $this->hasRole(
            'student'
        );
    }

    public function isParent(): bool
    {
        return $this->hasRole(
            'parent'
        );
    }

    /**
     * =========================================================
     * STATUS HELPERS
     * =========================================================
     */

    public function isActive(): bool
    {
        return (bool) $this->status;
    }

    /**
     * =========================================================
     * SCOPES
     * =========================================================
     */

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            1
        );
    }

    public function scopeInactive($query)
    {
        return $query->where(
            'status',
            0
        );
    }
}