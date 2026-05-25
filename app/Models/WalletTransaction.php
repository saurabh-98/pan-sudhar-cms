<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{

    protected $fillable = [

        'user_id',
        'receiver_id',
        'amount',
        'type',
        'transaction_type',
        'remark',
        'remarks'

    ];



    /*
    |--------------------------------------------------------------------------
    | USER RELATION
    |--------------------------------------------------------------------------
    */

    public function user()
    {

        return $this->belongsTo(User::class);

    }



    /*
    |--------------------------------------------------------------------------
    | RECEIVER RELATION
    |--------------------------------------------------------------------------
    */

    public function receiver()
    {

        return $this->belongsTo(User::class, 'receiver_id');

    }

}