<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [

        'title',
        'content',
        'background',
        'status'
    ];

    protected $casts = [

        'status' => 'boolean',
    ];

    /* =========================================================
     | RELATIONSHIP
     *=========================================================*/

    public function certificates()
    {
        return $this->hasMany(
            Certificate::class,
            'template_id'
        );
    }
}