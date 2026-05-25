<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $fillable = [
        'student_id',
        'date',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}