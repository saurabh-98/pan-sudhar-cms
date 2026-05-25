<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
// ❌ REMOVE THIS
// use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionVerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admission;

    public function __construct($admission)
    {
        $this->admission = $admission;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Admission Verified - Proceed to Payment'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admission_verified',
            with: [
                'admission' => $this->admission
            ]
        );
    }
}