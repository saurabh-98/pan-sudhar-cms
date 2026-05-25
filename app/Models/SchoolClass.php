<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';

    protected $fillable = [

        'name',
        'description',
        'status'

    ];

    /* ================= RELATIONS ================= */

    /*
    |--------------------------------------------------------------------------
    | CLASS → SECTIONS
    |--------------------------------------------------------------------------
    */

    public function sections()
    {
        return $this->hasMany(

            Section::class,

            'class_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS → STUDENTS
    |--------------------------------------------------------------------------
    */

    public function students()
    {
        return $this->hasMany(

            Student::class,

            'class_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS → ADMISSIONS
    |--------------------------------------------------------------------------
    */

    public function admissions()
    {
        return $this->hasMany(

            Admission::class,

            'class_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS → SUBJECTS
    |--------------------------------------------------------------------------
    */

    public function subjects()
    {
        return $this->hasMany(

            Subject::class,

            'class_id'

        );
    }
}