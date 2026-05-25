<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admission;

use Carbon\Carbon;

class Certificate extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'certificates';

    /*
    |--------------------------------------------------------------------------
    | CERTIFICATE TYPES
    |--------------------------------------------------------------------------
    */

    public const TYPE_BONAFIDE = 'Bonafide';

    public const TYPE_CHARACTER = 'Character';

    public const TYPE_TRANSFER = 'Transfer';

    public const TYPE_COMPLETION = 'Completion';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | RELATIONS
        |--------------------------------------------------------------------------
        */

        'student_id',

        'employee_id',

        /*
        |--------------------------------------------------------------------------
        | CERTIFICATE
        |--------------------------------------------------------------------------
        */

        'certificate_no',

        'certificate_type',

        'template_id',

        'issue_date',

        'remarks',

        /*
        |--------------------------------------------------------------------------
        | FILE
        |--------------------------------------------------------------------------
        */

        'file',

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        'generated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'issue_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'formatted_issue_date',

        'file_url',

        'certificate_label'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

  

    public function student()
    {
        return $this->belongsTo(

            Admission::class,

            'student_id'
        );
    }

    public function employee()
    {
        return $this->belongsTo(

            Employee::class
        );
    }

    public function template()
    {
        return $this->belongsTo(

            CertificateTemplate::class,

            'template_id'
        );
    }

    public function generator()
    {
        return $this->belongsTo(

            User::class,

            'generated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getFormattedIssueDateAttribute()
    {
        return $this->issue_date

            ? Carbon::parse(

                $this->issue_date

            )->format('d M Y')

            : '-';
    }

    public function getFileUrlAttribute()
    {
        return $this->file

            ? asset(

                'uploads/certificates/' . $this->file
            )

            : asset(

                'images/no-file.png'
            );
    }

    public function getCertificateLabelAttribute()
    {
        return strtoupper(

            $this->certificate_type
        ) . ' CERTIFICATE';
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeLatestFirst($query)
    {
        return $query->latest('id');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where(

            'student_id',

            $studentId
        );
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where(

            'employee_id',

            $employeeId
        );
    }

    public function scopeType($query, $type)
    {
        return $query->where(

            'certificate_type',

            $type
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isStudentCertificate()
    {
        return !is_null(

            $this->student_id
        );
    }

    public function isEmployeeCertificate()
    {
        return !is_null(

            $this->employee_id
        );
    }

    public function hasFile()
    {
        return !empty(

            $this->file
        );
    }
}