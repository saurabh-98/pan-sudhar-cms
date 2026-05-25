<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Career;

use App\Models\Application;

class CareerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CAREER LIST
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $careers = Career::where('is_active', 1)
                    ->latest()
                    ->paginate(12);

        return view(

            'frontend.careers.index',

            compact('careers')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CAREER DETAILS
    |--------------------------------------------------------------------------
    */

    public function show($slug)
    {
        $career = Career::where(

                        'slug',

                        $slug

                    )->firstOrFail();

        return view(

            'frontend.careers.show',

            compact('career')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPLY JOB
    |--------------------------------------------------------------------------
    */

    public function apply(
    Request $request,
    $id
)
{
    try {

       /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

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

            'phone' => [

                'required',
                'string',
                'max:20'
            ],

            'resume' => [

                'required',

                'mimes:pdf,doc,docx',

                'max:2048'
            ],

            'cover_letter' => [

                'nullable',
                'string'
            ],

            /*
            |--------------------------------------------------------------------------
            | CAPTCHA VALIDATION
            |--------------------------------------------------------------------------
            */

            'g-recaptcha-response' => [

                'required',

                function (
                    $attribute,
                    $value,
                    $fail
                ){

                    $secret =
                    env('RECAPTCHA_SECRET_KEY');

                    $verify =
                    file_get_contents(

                        "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$value}"
                    );

                    $captchaSuccess =
                    json_decode($verify);

                    if(
                        !$captchaSuccess ||
                        !$captchaSuccess->success
                    ){

                        $fail(
                            'Captcha verification failed.'
                        );
                    }

                }
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | CAREER CHECK
        |--------------------------------------------------------------------------
        */

        $career = Career::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | DUPLICATE APPLICATION CHECK
        |--------------------------------------------------------------------------
        */

        $alreadyApplied = Application::where(

                                'career_id',
                                $career->id

                            )
                            ->where(

                                'email',
                                $request->email

                            )
                            ->exists();

        if($alreadyApplied){

            return response()->json([

                'status' => false,

                'message' =>
                'You have already applied for this job.'

            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | RESUME UPLOAD
        |--------------------------------------------------------------------------
        */

        $resumePath = null;

        if($request->hasFile('resume')){

            $resumeFile = $request->file('resume');

            /*
            |--------------------------------------------------------------------------
            | SAFE FILE NAME
            |--------------------------------------------------------------------------
            */

            $fileName =
                time() . '_' .
                uniqid() . '.' .
                $resumeFile->getClientOriginalExtension();

            /*
            |--------------------------------------------------------------------------
            | STORE FILE
            |--------------------------------------------------------------------------
            */

            $resumePath = $resumeFile->storeAs(

                'resumes',

                $fileName,

                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STORE APPLICATION
        |--------------------------------------------------------------------------
        */

        $application = Application::create([

            'career_id' => $career->id,

            'name' => strip_tags($request->name),

            'email' => strtolower($request->email),

            'phone' => strip_tags($request->phone),

            'resume' => $resumePath,

            'cover_letter' =>
            strip_tags($request->cover_letter),

            'status' => 'pending'
        ]);

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => true,

            'message' =>
            'Application submitted successfully.',

            'data' => $application

        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION ERROR
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => false,

            'errors' => $e->errors()

        ], 422);

    } catch (\Exception $e) {

        /*
        |--------------------------------------------------------------------------
        | SERVER ERROR
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'status' => false,

            'message' => $e->getMessage()

        ], 500);
    }
}

}