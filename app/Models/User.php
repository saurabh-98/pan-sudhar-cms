<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Fillable
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role' // admin | staff | customer
    ];

    /**
     * Hidden
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * =========================
     * RELATIONSHIPS
     * =========================
     */

    // Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * =========================
     * ROLE CHECKS
     * =========================
     */

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * =========================
     * PERMISSION SYSTEM
     * =========================
     */

    public function hasPermission($permission)
    {
        $permissions = $this->rolePermissions();

        return in_array('*', $permissions) || in_array($permission, $permissions);
    }

    /**
     * Role-wise permissions
     */
    private function rolePermissions()
    {
        return [

            'admin' => [
                '*' // full access
            ],

            'staff' => [
                'view_dashboard',
                'view_orders',
                'update_orders',
                'view_menu',
                'view_reservations'
            ],

            'customer' => [
                'view_menu',
                'place_order'
            ]

        ][$this->role] ?? [];
    }

    /**
     * =========================
     * SHORTCUT HELPERS
     * =========================
     */

    public function canViewOrders()
    {
        return $this->hasPermission('view_orders');
    }

    public function canManageUsers()
    {
        return $this->hasPermission('manage_users');
    }

    public function canManageMenu()
    {
        return $this->hasPermission('view_menu');
    }

}