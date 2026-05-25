<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;
    public $receiptPdf;
    public $idCardPdf;
    public $regNo;

    public function __construct($name, $email, $password, $receiptPdf = null, $idCardPdf = null, $regNo = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->receiptPdf = $receiptPdf;
        $this->idCardPdf = $idCardPdf;
        $this->regNo = $regNo;
    }

    public function build()
    {
        $mail = $this->subject('👨‍👩‍👦 Parent Login Credentials')
            ->view('emails.parent_credentials')
            ->with([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password
            ]);

        /* 🔥 ATTACH RECEIPT */
        if ($this->receiptPdf) {
            $mail->attachData(
                $this->receiptPdf,
                'Receipt_' . ($this->regNo ?? 'student') . '.pdf',
                ['mime' => 'application/pdf']
            );
        }

        /* 🔥 ATTACH ID CARD */
        if ($this->idCardPdf) {
            $mail->attachData(
                $this->idCardPdf,
                'IDCard_' . ($this->regNo ?? 'student') . '.pdf',
                ['mime' => 'application/pdf']
            );
        }

        return $mail;
    }
}