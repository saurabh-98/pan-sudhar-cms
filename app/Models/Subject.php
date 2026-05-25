<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'class_id',
        'section_id',
        'name',
        'status'
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class);
    }

   

    public function section()
    {
        return $this->belongsTo(

            Section::class,

            'section_id'

        );
    }
    public function schoolClass()
    {
        return $this->belongsTo(

            SchoolClass::class,

            'class_id'

        );
    }
}