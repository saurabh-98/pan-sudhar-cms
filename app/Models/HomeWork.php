<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
  
    protected $table = 'homeworks'; 
    protected $fillable = [
        'class_id',
        'subject_id',
        'title',
        'description',
        'assigned_date',
        'due_date'
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}