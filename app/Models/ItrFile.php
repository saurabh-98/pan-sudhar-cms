<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ItrFile extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'itr_files';



    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'user_id',

        'aadhaar_front',

        'aadhaar_back',

        'pan_card',

        'name',

        'email',

        'remarks',

        'charge',

        'status',

        'assigned_to',

        'assigned_at',

        'admin_remarks',

    ];



    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'assigned_at' => 'datetime',

    ];



    /*
    |--------------------------------------------------------------------------
    | USER RELATION
    |--------------------------------------------------------------------------
    */

    public function user()
    {

        return $this->belongsTo(
            User::class,
            'user_id'
        );

    }



    /*
    |--------------------------------------------------------------------------
    | ASSIGNED EMPLOYEE
    |--------------------------------------------------------------------------
    */

    public function assignedEmployee()
    {

        return $this->belongsTo(
            User::class,
            'assigned_to'
        );

    }



    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute()
    {

        if($this->status == 'approved')
        {

            return '

                <span class="badge bg-success">

                    Approved

                </span>

            ';

        }

        elseif($this->status == 'pending')
        {

            return '

                <span class="badge bg-warning text-dark">

                    Pending

                </span>

            ';

        }

        elseif($this->status == 'processing')
        {

            return '

                <span class="badge bg-info">

                    Processing

                </span>

            ';

        }

        return '

            <span class="badge bg-danger">

                Rejected

            </span>

        ';

    }



    /*
    |--------------------------------------------------------------------------
    | AADHAAR FRONT URL
    |--------------------------------------------------------------------------
    */

    public function getAadhaarFrontUrlAttribute()
    {

        if($this->aadhaar_front)
        {

            return asset(
                'storage/' . $this->aadhaar_front
            );

        }

        return null;

    }



    /*
    |--------------------------------------------------------------------------
    | AADHAAR BACK URL
    |--------------------------------------------------------------------------
    */

    public function getAadhaarBackUrlAttribute()
    {

        if($this->aadhaar_back)
        {

            return asset(
                'storage/' . $this->aadhaar_back
            );

        }

        return null;

    }



    /*
    |--------------------------------------------------------------------------
    | PAN CARD URL
    |--------------------------------------------------------------------------
    */

    public function getPanCardUrlAttribute()
    {

        if($this->pan_card)
        {

            return asset(
                'storage/' . $this->pan_card
            );

        }

        return null;

    }



    /*
    |--------------------------------------------------------------------------
    | ASSIGNED USER NAME
    |--------------------------------------------------------------------------
    */

    public function getAssignedUserNameAttribute()
    {

        return $this->assignedEmployee->name
            ?? 'Not Assigned';

    }

    public function documents()
    {
        return $this->hasMany(

            ServiceDocument::class,

            'service_id'

        )->where(

            'service_type',

            'pan'

        );
    }
    

}