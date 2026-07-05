<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDocs extends Model
{
    protected $fillable = [

        'service_code',
        'title',
        'pdf',
        'description',
        'is_active'

    ];
}