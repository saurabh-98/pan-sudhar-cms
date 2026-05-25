<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'exams';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'class_id',

        'section_id',

        'name',

        'exam_type',

        'description',

        'start_date',

        'end_date',

        'total_marks',

        'passing_marks',

        'status'

    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'start_date' => 'date',

        'end_date' => 'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | CLASS
    |--------------------------------------------------------------------------
    */

    public function schoolClass()
    {
        return $this->belongsTo(

            SchoolClass::class,

            'class_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | SECTION
    |--------------------------------------------------------------------------
    */

    public function section()
    {
        return $this->belongsTo(

            Section::class,

            'section_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXAM SUBJECTS
    |--------------------------------------------------------------------------
    */

    public function subjects()
    {
        return $this->hasMany(

            ExamSubject::class,

            'exam_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESULTS
    |--------------------------------------------------------------------------
    */

    public function results()
    {
        return $this->hasMany(

            Result::class,

            'exam_id'

        );
    }
}