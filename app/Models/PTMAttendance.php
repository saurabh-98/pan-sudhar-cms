<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PTMAttendance extends Model
{
    protected $table = 'ptm_attendances';
    protected $fillable = [

        'ptm_meeting_id',
        'student_id',
        'parent_id',
        'attended',
        'remarks'
    ];

    public function meeting()
    {
        return $this->belongsTo(
            PTMMeeting::class,
            'ptm_meeting_id'
        );
    }
}