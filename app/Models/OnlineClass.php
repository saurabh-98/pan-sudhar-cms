<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| MODELS
|--------------------------------------------------------------------------
*/

use App\Models\Teacher;

use App\Models\SchoolClass;

use App\Models\Section;

use App\Models\Subject;

class OnlineClass extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'online_classes';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | CLASS INFO
        |--------------------------------------------------------------------------
        */

        'class_id',

        'section_id',

        'subject_id',

        'teacher_id',

        /*
        |--------------------------------------------------------------------------
        | CLASS DETAILS
        |--------------------------------------------------------------------------
        */

        'title',

        'description',

        /*
        |--------------------------------------------------------------------------
        | DATE & TIME
        |--------------------------------------------------------------------------
        */

        'class_date',

        'start_time',

        'end_time',

        /*
        |--------------------------------------------------------------------------
        | MEETING
        |--------------------------------------------------------------------------
        */

        'meeting_link',

        'meeting_id',

        'meeting_password',

        'platform',

        /*
        |--------------------------------------------------------------------------
        | RECORDING
        |--------------------------------------------------------------------------
        */

        'recording_link',

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        'status',

    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'class_date' => 'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'formatted_date',

        'formatted_start_time',

        'formatted_end_time',

        'formatted_time',

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
    | SUBJECT
    |--------------------------------------------------------------------------
    */

    public function subject()
    {
        return $this->belongsTo(

            Subject::class,

            'subject_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER
    |--------------------------------------------------------------------------
    */

    public function teacher()
    {
        return $this->belongsTo(

            Teacher::class,

            'teacher_id'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORMATTED DATE
    |--------------------------------------------------------------------------
    */

    public function getFormattedDateAttribute()
    {
        if(!$this->class_date){

            return '-';
        }

        return Carbon::parse(

            $this->class_date

        )->format('d M Y');
    }

    /*
    |--------------------------------------------------------------------------
    | FORMATTED START TIME
    |--------------------------------------------------------------------------
    */

    public function getFormattedStartTimeAttribute()
    {
        if(!$this->start_time){

            return '-';
        }

        return Carbon::parse(

            $this->start_time

        )->format('h:i A');
    }

    /*
    |--------------------------------------------------------------------------
    | FORMATTED END TIME
    |--------------------------------------------------------------------------
    */

    public function getFormattedEndTimeAttribute()
    {
        if(!$this->end_time){

            return '-';
        }

        return Carbon::parse(

            $this->end_time

        )->format('h:i A');
    }

    /*
    |--------------------------------------------------------------------------
    | FORMATTED TIME RANGE
    |--------------------------------------------------------------------------
    */

    public function getFormattedTimeAttribute()
    {
        if(

            !$this->start_time ||

            !$this->end_time

        ){

            return '-';
        }

        return

            Carbon::parse(

                $this->start_time

            )->format('h:i A')

            .

            ' - '

            .

            Carbon::parse(

                $this->end_time

            )->format('h:i A');
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute()
    {
        if($this->status == 'live'){

            return '

                <span class="badge bg-success">

                    Live

                </span>

            ';
        }

        if($this->status == 'completed'){

            return '

                <span class="badge bg-primary">

                    Completed

                </span>

            ';
        }

        return '

            <span class="badge bg-warning text-dark">

                Scheduled

            </span>

        ';
    }

    /*
    |--------------------------------------------------------------------------
    | LIVE STATUS
    |--------------------------------------------------------------------------
    */

    public function isLive()
    {
        return $this->status === 'live';
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETED STATUS
    |--------------------------------------------------------------------------
    */

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /*
    |--------------------------------------------------------------------------
    | SCHEDULED STATUS
    |--------------------------------------------------------------------------
    */

    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }
}