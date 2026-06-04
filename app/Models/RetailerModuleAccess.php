<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerModuleAccess extends Model
{
    protected $table =
        'retailer_module_access';

    protected $fillable = [

        'retailer_id',

        'module_id',

    ];

    public function retailer()
    {
        return $this->belongsTo(
            User::class,
            'retailer_id'
        );
    }

    public function module()
    {
        return $this->belongsTo(
            Module::class,
            'module_id'
        );
    }
}