<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

use App\Models\Message;
use App\Models\WalletTransaction;
use App\Models\Order;
use App\Models\BookIssue;
use App\Models\Leave;
use App\Models\PanApplication;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /*
    |--------------------------------------------------------------------------
    | SPATIE GUARD
    |--------------------------------------------------------------------------
    */

    protected $guard_name = 'web';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'name',

        'email',

        'mobile',

        'password',

        'image',

        'status',

        'first_login',

        'wallet_balance',
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

    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',

            'status' => 'boolean',

            'first_login' => 'boolean',

            'wallet_balance' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'role_name',

        'role_badge',

        'image_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLE NAME
    |--------------------------------------------------------------------------
    */

    public function getRoleNameAttribute(): string
    {
        return $this->getRoleNames()
            ->first() ?? 'No Role';
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE BADGE
    |--------------------------------------------------------------------------
    */

    public function getRoleBadgeAttribute(): string
    {
        $role = strtolower(
            $this->role_name
        );

        return match (true) {

            str_contains($role, 'admin') =>

                'danger',

            str_contains($role, 'retailer') =>

                'primary',

            str_contains($role, 'distributor') =>

                'warning',

            str_contains($role, 'employee') =>

                'info',

            default =>

                'secondary',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | IMAGE URL
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute(): ?string
    {
        return $this->image

            ? asset(
                'storage/'.$this->image
            )

            : asset(
                'assets/images/default-user.png'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SENT MESSAGES
    |--------------------------------------------------------------------------
    */

    public function sentMessages()
    {
        return $this->hasMany(

            Message::class,

            'sender_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIVED MESSAGES
    |--------------------------------------------------------------------------
    */

    public function receivedMessages()
    {
        return $this->hasMany(

            Message::class,

            'receiver_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WALLET TRANSACTIONS
    |--------------------------------------------------------------------------
    */

    public function walletTransactions()
    {
        return $this->hasMany(

            WalletTransaction::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAN APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function panApplications()
    {
        return $this->hasMany(

            PanApplication::class,

            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGNED PAN APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function assignedPanApplications()
    {
        return $this->hasMany(

            PanApplication::class,

            'assigned_to'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */

    public function orders()
    {
        return $this->hasMany(

            Order::class
        );
    }

    
    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->hasRole(
            'Admin'
        );
    }

    public function isRetailer(): bool
    {
        return $this->hasRole(
            'Retailer'
        );
    }

    public function isDistributor(): bool
    {
        return $this->hasRole(
            'Distributor'
        );
    }

    public function isEmployee(): bool
    {
        return $this->hasRole(
            'Employee'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return (bool) $this->status;
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(

            'status',

            1

        );
    }

    /*
    |--------------------------------------------------------------------------
    | INACTIVE SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeInactive($query)
    {
        return $query->where(

            'status',

            0

        );
    }


    /*
|--------------------------------------------------------------------------
| RETAILER
|--------------------------------------------------------------------------
*/

public function retailer()
{
    return $this->hasOne(

        Retailer::class,

        'email',

        'email'

    );
}

public function retailerModules()
{
    return $this->belongsToMany(
        Module::class,
        'retailer_module_access',
        'retailer_id',
        'module_id'
    );
}


/*
|--------------------------------------------------------------------------
| DISTRIBUTOR RETAILERS
|--------------------------------------------------------------------------
*/

public function retailers()
{
    return $this->hasMany(

        Retailer::class,

        'distributor_id'

    );
}

public function retailerSessions()
{
    return $this->hasMany(RetailerSession::class, 'retailer_id');
}

public function latestRetailerSession()
{
    return $this->hasOne(RetailerSession::class, 'retailer_id')
                ->latestOfMany('last_activity_at');
}

}