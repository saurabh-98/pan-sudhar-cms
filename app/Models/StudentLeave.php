<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLeave extends Model
{
    protected $fillable = [

        'student_id',

        'type',

        'from_date',

        'to_date',

        'reason',

        'status',

        'document',

    ];

    protected $casts = [

        'from_date' => 'date',

        'to_date'   => 'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(
            User::class,
            'student_id'
        );
    }
}