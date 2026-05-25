<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentVerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    public $admission;

    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct($admission)
    {
        $this->admission = $admission;
    }

    /*
    |--------------------------------------------------------------------------
    | BUILD MAIL
    |--------------------------------------------------------------------------
    */

    public function build()
    {
        return $this

            ->subject(

                'Payment Verified - Admission'
            )

            ->view(

                'emails.payment_verified'
            );
    }
}