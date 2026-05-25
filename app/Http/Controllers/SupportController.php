<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Models\SupportTicket;

class SupportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('frontend.support');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE SUPPORT TICKET
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(

            $request->all(),

            [

                'name' => [

                    'required',
                    'string',
                    'max:255'
                ],

                'email' => [

                    'required',
                    'email',
                    'max:255'
                ],

                'mobile' => [

                    'required',
                    'digits:10'
                ],

                'subject' => [

                    'required',
                    'string',
                    'max:255'
                ],

                'message' => [

                    'required',
                    'string',
                    'min:10',
                    'max:5000'
                ],

                'priority' => [

                    'nullable',
                    'in:low,medium,high'
                ],

                'attachment' => [

                    'nullable',
                    'file',
                    'mimes:jpg,jpeg,png,pdf',
                    'max:2048'
                ],

                'g-recaptcha-response' => [

                    'required'
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDATION FAILED
        |--------------------------------------------------------------------------
        */

        if ($validator->fails()) {

            return response()->json([

                'success' => false,

                'errors' =>

                    $validator->errors()

            ], 422);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | VERIFY RECAPTCHA
            |--------------------------------------------------------------------------
            */

            $response = file_get_contents(

                "https://www.google.com/recaptcha/api/siteverify?secret="

                . env('RECAPTCHA_SECRET_KEY')

                . "&response="

                . $request->input('g-recaptcha-response')
            );

            $responseKeys = json_decode(

                $response,

                true
            );

            if (
                empty($responseKeys['success'])
            ) {

                return response()->json([

                    'success' => false,

                    'message' =>

                        'Captcha verification failed'

                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | SECURE FILE UPLOAD
            |--------------------------------------------------------------------------
            */

            $attachment = null;

            if ($request->hasFile('attachment')) {

                $file = $request->file('attachment');

                /*
                |--------------------------------------------------------------------------
                | MIME CHECK
                |--------------------------------------------------------------------------
                */

                $allowedMime = [

                    'image/jpeg',
                    'image/png',
                    'application/pdf'
                ];

                if (
                    !in_array(

                        $file->getMimeType(),

                        $allowedMime
                    )
                ) {

                    return response()->json([

                        'success' => false,

                        'message' =>

                            'Invalid attachment type'

                    ], 422);
                }

                /*
                |--------------------------------------------------------------------------
                | FILE NAME
                |--------------------------------------------------------------------------
                */

                $fileName =

                    time()

                    . '_'

                    . uniqid()

                    . '.'

                    . $file->getClientOriginalExtension();

                /*
                |--------------------------------------------------------------------------
                | STORE FILE
                |--------------------------------------------------------------------------
                */

                $attachment = $file->storeAs(

                    'support',

                    $fileName,

                    'public'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | UNIQUE TICKET NUMBER
            |--------------------------------------------------------------------------
            */

            do {

                $ticketNo =

                    'SUP-'

                    . date('Y')

                    . '-'

                    . strtoupper(

                        substr(

                            uniqid(),

                            -6
                        )
                    );

            } while (

                SupportTicket::where(

                    'ticket_no',

                    $ticketNo

                )->exists()
            );

            /*
            |--------------------------------------------------------------------------
            | SAVE TICKET
            |--------------------------------------------------------------------------
            */

            $ticket = SupportTicket::create([

                'ticket_no' => $ticketNo,

                'name' => strip_tags($request->name),

                'email' => strip_tags($request->email),

                'mobile' => strip_tags($request->mobile),

                'subject' => strip_tags($request->subject),

                'message' => strip_tags($request->message),

                'attachment' => $attachment,

                'priority' =>

                    $request->priority
                    ?? 'medium',

                'status' => 'open',
            ]);

            /*
            |--------------------------------------------------------------------------
            | SEND USER EMAIL
            |--------------------------------------------------------------------------
            */

            Mail::raw(

                "Dear "

                . $ticket->name

                . ",\n\n"

                . "Your support ticket has been submitted successfully.\n\n"

                . "Ticket Number: "

                . $ticket->ticket_no

                . "\n\n"

                . "Subject: "

                . $ticket->subject

                . "\n\n"

                . "Our support team will contact you shortly.\n\n"

                . "Thank You\n"

                . config('app.name'),

                function ($mail) use ($ticket) {

                    $mail->to(

                        $ticket->email
                    )

                    ->subject(

                        'Support Ticket Created'
                    );
                }
            );

            /*
            |--------------------------------------------------------------------------
            | SEND ADMIN EMAIL
            |--------------------------------------------------------------------------
            */

            if (env('MAIL_ADMIN_ADDRESS')) {

                Mail::raw(

                    "New support ticket received.\n\n"

                    . "Ticket No: "

                    . $ticket->ticket_no

                    . "\n"

                    . "Name: "

                    . $ticket->name

                    . "\n"

                    . "Subject: "

                    . $ticket->subject,

                    function ($mail) {

                        $mail->to(

                            env('MAIL_ADMIN_ADDRESS')
                        )

                        ->subject(

                            'New Support Ticket'
                        );
                    }
                );
            }

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | SUCCESS RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => true,

                'message' =>

                    'Support ticket submitted successfully.',

                'ticket_no' =>

                    $ticket->ticket_no
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(

                'Support Ticket Error',

                [

                    'message' =>

                        $e->getMessage(),

                    'line' =>

                        $e->getLine(),

                    'file' =>

                        $e->getFile(),
                ]
            );

            return response()->json([

                'success' => false,

                'message' => config('app.debug')

                    ? $e->getMessage()

                    : 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TRACK PAGE
    |--------------------------------------------------------------------------
    */

    public function track()
    {
        return view('frontend.support-track');
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH TICKET
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        $request->validate([

            'ticket_no' => [

                'required',
                'string'
            ],

            'email' => [

                'required',
                'email'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIND TICKET
        |--------------------------------------------------------------------------
        */

        $ticket = SupportTicket::where(

            'ticket_no',

            trim($request->ticket_no)

        )->where(

            'email',

            trim($request->email)

        )->first();

        /*
        |--------------------------------------------------------------------------
        | NOT FOUND
        |--------------------------------------------------------------------------
        */

        if (!$ticket) {

            return back()

                ->withInput()

                ->with(

                    'error',

                    'Invalid ticket number or email address.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(

            'frontend.support-track',

            compact('ticket')
        );
    }
}