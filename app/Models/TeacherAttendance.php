<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $fillable = [
        'teacher_id',
        'date',
        'status'
    ];

    /* ===============================
       RELATIONSHIPS
    =============================== */

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

}