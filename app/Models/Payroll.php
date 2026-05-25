<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',

        // 🔥 Salary Structure
        'basic',
        'hra',
        'bonus',
        'pf',

        // 🔥 Calculated
        'gross_salary',
        'deductions',
        'net_salary',
        'status'
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',

        'basic' => 'float',
        'hra' => 'float',
        'bonus' => 'float',
        'pf' => 'float',

        'gross_salary' => 'float',
        'deductions' => 'float',
        'net_salary' => 'float',
    ];

    /* ================= RELATION ================= */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}