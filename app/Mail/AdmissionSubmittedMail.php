<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Barryvdh\DomPDF\Facade\Pdf;

class AdmissionSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

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
    | SUBJECT
    |--------------------------------------------------------------------------
    */

    public function envelope(): Envelope
    {
        return new Envelope(

            subject: 'Admission Submitted Successfully'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EMAIL VIEW
    |--------------------------------------------------------------------------
    */

    public function content(): Content
    {
        return new Content(

            view: 'emails.admission-submitted',

            with: [

                'admission' => $this->admission
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PDF ATTACHMENT
    |--------------------------------------------------------------------------
    */

    public function attachments(): array
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | MEMORY LIMIT
            |--------------------------------------------------------------------------
            */

            ini_set('memory_limit', '512M');

            /*
            |--------------------------------------------------------------------------
            | LOAD PDF
            |--------------------------------------------------------------------------
            */

            $pdf = Pdf::loadView(

                'pdf.admission-receipt',

                [

                    'admission' => $this->admission
                ]
            )

            ->setPaper('a4');

            /*
            |--------------------------------------------------------------------------
            | ATTACH PDF
            |--------------------------------------------------------------------------
            */

            return [

                Attachment::fromData(

                    fn () => $pdf->output(),

                    'Admission-' .

                    ($this->admission->application_no ?? 'Receipt')

                    . '.pdf'
                )

                ->withMime('application/pdf'),
            ];

        } catch (\Throwable $e) {

            \Log::error(

                'PDF Attachment Error',

                [

                    'error' => $e->getMessage(),

                    'line' => $e->getLine(),

                    'file' => $e->getFile()
                ]
            );

            return [];
        }
    }
}