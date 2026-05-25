<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'sections';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'class_id',

        'name',

        'capacity',

        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'filled_seats',

        'is_full',

        'status_badge'
    ];

    /*
    |--------------------------------------------------------------------------
    | CLASSROOM
    |--------------------------------------------------------------------------
    */

    public function classroom()
    {
        return $this->belongsTo(

            SchoolClass::class,

            'class_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    */

    public function students()
    {
        return $this->hasMany(

            Student::class,

            'section_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASSES
    |--------------------------------------------------------------------------
    */

    public function onlineClasses()
    {
        return $this->hasMany(

            OnlineClass::class,

            'section_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FILLED SEATS
    |--------------------------------------------------------------------------
    */

    public function getFilledSeatsAttribute()
    {
        return

            $this->students_count

            ??

            $this->students()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | FULL CHECK
    |--------------------------------------------------------------------------
    */

    public function getIsFullAttribute()
    {
        return

            $this->filled_seats

            >=

            $this->capacity;
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute()
    {
        if($this->status){

            return '

                <span class="badge bg-success">

                    Active

                </span>

            ';
        }

        return '

            <span class="badge bg-danger">

                Inactive

            </span>

        ';
    }

    /*
    |--------------------------------------------------------------------------
    | NAME MUTATOR
    |--------------------------------------------------------------------------
    */

    public function setNameAttribute($value)
    {
        $this->attributes['name']

            = strtoupper($value);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE CHECK
    |--------------------------------------------------------------------------
    */

    public function isActive()
    {
        return (bool) $this->status;
    }
}