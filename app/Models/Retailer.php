<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Retailer extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'retailers';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | BASIC DETAILS
        |--------------------------------------------------------------------------
        */

        'shop_name',
        'name',
        'mobile',
        'email',

        /*
        |--------------------------------------------------------------------------
        | LOCATION
        |--------------------------------------------------------------------------
        */

        'state_id',
        'district_id',
        'distributor_id',

        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        'password',

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        'status',
        'is_verified',

        /*
        |--------------------------------------------------------------------------
        | VERIFICATION
        |--------------------------------------------------------------------------
        */

        'email_verified_at',

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        'remember_token',

        /*
        |--------------------------------------------------------------------------
        | META
        |--------------------------------------------------------------------------
        */

        'registered_ip',
        'last_login_at',

    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */

    protected $hidden = [

        'password',
        'remember_token',

    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'email_verified_at' => 'datetime',

        'last_login_at' => 'datetime',

        'is_verified' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | STATE RELATION
    |--------------------------------------------------------------------------
    */

    public function state()
    {
        return $this->belongsTo(
            State::class,
            'state_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISTRICT RELATION
    |--------------------------------------------------------------------------
    */

    public function district()
    {
        return $this->belongsTo(
            District::class,
            'district_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISTRIBUTOR RELATION
    |--------------------------------------------------------------------------
    */

    public function distributor()
    {
        return $this->belongsTo(
            User::class,
            'distributor_id'
        );
    }

    public function retailerSessions()
    {
        return $this->hasMany(RetailerSession::class, 'retailer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS LABEL
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {

            'approved' =>
                '<span class="badge bg-success">Approved</span>',

            'pending' =>
                '<span class="badge bg-warning">Pending</span>',

            'blocked' =>
                '<span class="badge bg-danger">Blocked</span>',

            'rejected' =>
                '<span class="badge bg-secondary">Rejected</span>',

            default =>
                '<span class="badge bg-dark">Unknown</span>',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | FULL ADDRESS
    |--------------------------------------------------------------------------
    */

    public function getFullAddressAttribute()
    {
        return optional($this->district)->name
            . ', ' .
            optional($this->state)->name;
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIED LABEL
    |--------------------------------------------------------------------------
    */

    public function getVerifiedLabelAttribute()
    {
        return $this->is_verified
            ? 'Verified'
            : 'Not Verified';
    }
}