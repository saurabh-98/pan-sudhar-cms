<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;
    public $regNo;

    public $receiptPdf;
    public $idCardPdf;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $name,
        $email,
        $password,
        $regNo,
        $receiptPdf = null,
        $idCardPdf = null
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->regNo = $regNo;
        $this->receiptPdf = $receiptPdf;
        $this->idCardPdf = $idCardPdf;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        try {

            $mail = $this->subject('🎓 Admission Approved - Login Credentials')
                ->view('emails.student_credentials')
                ->with([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => $this->password,
                    'regNo' => $this->regNo
                ]);

            // ✅ Attach Receipt
            if (!empty($this->receiptPdf)) {
                $mail->attachData(
                    $this->receiptPdf,
                    'receipt.pdf',
                    ['mime' => 'application/pdf']
                );
            }

            // ✅ Attach ID Card
            if (!empty($this->idCardPdf)) {
                $mail->attachData(
                    $this->idCardPdf,
                    'id-card.pdf',
                    ['mime' => 'application/pdf']
                );
            }

            return $mail;

        } catch (\Throwable $e) {

            \Log::error('Student Mail Error', [
                'error' => $e->getMessage()
            ]);

            // fallback: send without attachment
            return $this->subject('🎓 Admission Approved - Login Credentials')
                ->view('emails.student_credentials')
                ->with([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => $this->password,
                    'regNo' => $this->regNo
                ]);
        }
    }
}