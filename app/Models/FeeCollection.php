<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCollection extends Model
{
    protected $fillable = [

        'student_id',

        'class_id',

        'amount',

        'paid_amount',

        'due_amount',

        'status',

        'payment_date',

        'payment_method',

        'reference_no',

        'remarks'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(
            Student::class
        );
    }

    public function class()
    {
        return $this->belongsTo(
            SchoolClass::class
        );
    }
}