<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'basic',
        'hra',
        'allowances',
        'deductions',
        'net_salary'
    ];

    /* ================= RELATION ================= */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}