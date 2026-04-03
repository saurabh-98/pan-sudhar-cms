<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpiSetting extends Model
{
    protected $fillable = ['upi_id','name','is_active'];
}