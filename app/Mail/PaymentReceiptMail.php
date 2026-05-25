<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use SerializesModels;

    public $admission;
    public $pdf;

    public function __construct($admission, $pdf)
    {
        $this->admission = $admission;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Payment Receipt - '.$this->admission->name)
            ->view('emails.payment_receipt')
            ->attachData(
                $this->pdf,
                'payment_receipt_'.$this->admission->id.'.pdf',
                ['mime' => 'application/pdf']
            );
    }
}