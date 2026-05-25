<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'quantity',
        'available_quantity'
    ];

    // 🔗 Relationships
    public function issues()
    {
        return $this->hasMany(BookIssue::class);
    }
}