<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = [

        'title',
        'slug',
        'description',
        'department',
        'location',
        'employment_type',
        'salary',
        'last_date',
        'is_active'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}