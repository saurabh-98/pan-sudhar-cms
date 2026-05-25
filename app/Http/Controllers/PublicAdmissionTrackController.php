<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;

class PublicAdmissionTrackController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TRACK PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('frontend.track-admission');
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'application_no' => [

                'required',
                'string',
                'max:50'
            ],

            'father_mobile' => [

                'required',
                'digits:10'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIND ADMISSION
        |--------------------------------------------------------------------------
        */

        $admission = Admission::with([

            'studentClass',
            'section',
            'state',
            'district'

        ])

        ->where(

            'application_no',

            trim($request->application_no)
        )

        ->where(

            'father_mobile',

            trim($request->father_mobile)
        )

        ->first();

        /*
        |--------------------------------------------------------------------------
        | NOT FOUND
        |--------------------------------------------------------------------------
        */

        if (!$admission) {

            return back()

                ->withInput()

                ->with(

                    'error',

                    'Invalid application number or mobile number.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(

            'frontend.track-admission',

            compact('admission')
        );
    }
}