<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['name','capacity','is_active'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}