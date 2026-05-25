<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PTMMeeting extends Model
{

    protected $table = 'ptm_meetings';
    protected $fillable = [

        'title',
        'class_id',
        'section_id',
        'teacher_id',
        'meeting_date',
        'start_time',
        'end_time',
        'meeting_type',
        'meeting_link',
        'location',
        'agenda',
        'status'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class,'teacher_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class,'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class,'section_id');
    }
}