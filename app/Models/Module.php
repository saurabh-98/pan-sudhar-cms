<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [

        'name',

        'slug',

        'icon',

        'route_name',

        'parent_id',

        'sort_order',

        'status',

    ];

    protected $casts = [

        'status' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | PARENT MODULE
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(

            self::class,

            'parent_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHILD MODULES
    |--------------------------------------------------------------------------
    */

    public function children()
    {
        return $this->hasMany(

            self::class,

            'parent_id'

        )

        ->where(

            'status',

            1

        )

        ->orderBy(

            'sort_order'

        )

        ->orderBy(

            'name'

        );
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

    public function retailerAccess()
    {
        return $this->hasMany(
            RetailerModuleAccess::class,
            'module_id'
        );
    }
}