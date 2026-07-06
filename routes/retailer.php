<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Retailer\DashboardController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Retailer\WalletController;

/*
|--------------------------------------------------------------------------
| PAN CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Retailer\Pan\NewPanApplicationController;
use App\Http\Controllers\Retailer\Pan\PanCorrectionController;
use App\Http\Controllers\Retailer\Pan\PanApplyWithoutDocumentController;
use App\Http\Controllers\Retailer\Pan\PanTrainingController;
use App\Http\Controllers\Retailer\Pan\PanFindController;
use App\Http\Controllers\Retailer\Pan\PanVerifyController;

/*
|--------------------------------------------------------------------------
| AADHAR CONTROLLERS
|--------------------------------------------------------------------------
*/


use App\Http\Controllers\Retailer\Aadhaar\AadhaarServiceController;
use App\Http\Controllers\Retailer\Csc\CscServiceController;
use App\Http\Controllers\Retailer\VoterId\VoterIdServiceController;
use App\Http\Controllers\Retailer\BankAccount\BankAccountServiceController;
use App\Http\Controllers\Retailer\OtherService\OtherServiceController;


/*
|--------------------------------------------------------------------------
| ITR CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Retailer\Itr\FileItrController;
use App\Http\Controllers\Retailer\Itr\ItrHistoryController;
use App\Http\Controllers\Retailer\Itr\ItrCorrectionController;
use App\Http\Controllers\Retailer\Itr\Form16Controller;
use App\Http\Controllers\Retailer\Itr\GstReturnController;

/*
|--------------------------------------------------------------------------
| VERIFICATION CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Retailer\Verification\BankVerificationController;
use App\Http\Controllers\Retailer\Verification\VoterVerificationController;
use App\Http\Controllers\Retailer\Verification\RcVerificationController;
use App\Http\Controllers\Retailer\Verification\DlVerificationController;
use App\Http\Controllers\Retailer\Verification\GstVerificationController;
use App\Http\Controllers\Retailer\Verification\PassportVerificationController;

/*
|--------------------------------------------------------------------------
| TOOL CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Retailer\Tools\AadhaarPvcController;
use App\Http\Controllers\Retailer\Tools\HisabKitabController;
use App\Http\Controllers\Retailer\Tools\FileConverterController;
use App\Http\Controllers\Retailer\Tools\PassportPhotoController;

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/


    Route::controller(RegisterController::class)
    ->group(function () {

        Route::get(
            '/register',
            'showRegistrationForm'
        )->name('register');

        Route::post(
            '/register',
            'register'
        )->name('register.submit');

    });

    Route::controller(LoginController::class)
    ->group(function () {

        Route::get(
            '/login',
            'showLoginForm'
        )->name('login');

        Route::post(
            '/login',
            'login'
        )->name('login.submit');

    });





/*
|--------------------------------------------------------------------------
| AJAX DISTRICT
|--------------------------------------------------------------------------
*/

Route::get(

    '/get-districts/{stateId}',

    [RegisterController::class,
    'getDistricts']

)->name('get.districts');

/*
|--------------------------------------------------------------------------
| AUTH RETAILER
|--------------------------------------------------------------------------
*/

Route::middleware([

    'auth',
    'role:retailer'

])
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/dashboard',

        [DashboardController::class,
        'index']

    )->name('dashboard');

    
    /*
    |--------------------------------------------------------------------------
    | WALLET RECHARGE
    |--------------------------------------------------------------------------
    */

    Route::prefix('wallet')
        ->name('wallet.')
        ->controller(WalletController::class)
        ->group(function () {

            Route::get('/recharge', 'recharge')
                ->name('recharge');

            Route::post('/generate-qr', 'generateQr')
                ->name('generate-qr');

            Route::post('/submit-payment', 'submitPayment')
                ->name('submit-payment');

            Route::get('/recharge-history', 'rechargeHistory')
                ->name('recharge-history');

            Route::get('/recharge/{id}', 'showRecharge')
                ->name('recharge.show');

             Route::get(
                '/wallet/history',
                [WalletController::class, 'history']
            )->name('wallet.history');

        });


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/profile',

        [ProfileController::class,
        'index']

    )->name('profile');

  


    /*
|--------------------------------------------------------------------------
| NEW PAN APPLICATION MODULE
|--------------------------------------------------------------------------
*/

Route::prefix('pan')
->name('pan.')
->controller(NewPanApplicationController::class)
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | APPLY FORM
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/apply',
        'create'
    )->name('apply');

    /*
    |--------------------------------------------------------------------------
    | AJAX PREVIEW
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/preview',
        'preview'
    )->name('preview');

    /*
    |--------------------------------------------------------------------------
    | PREVIEW PAGE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/preview-page',
        'previewPage'
    )->name('preview.page');

    /*
    |--------------------------------------------------------------------------
    | FINAL SUBMIT
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/final-submit',
        'finalSubmit'
    )->name('final.submit');

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/store',
        'store'
    )->name('store');

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/history',
        'index'
    )->name('history');

    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/view/{id}',
        'show'
    )->name('view');

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/edit/{id}',
        'edit'
    )->name('edit');

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/update/{id}',
        'update'
    )->name('update');

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/delete/{id}',
        'destroy'
    )->name('delete');

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/status/{id}',
        'status'
    )->name('status');

    /*
    |--------------------------------------------------------------------------
    | ACKNOWLEDGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/acknowledgement/{id}',
        'acknowledgement'
    )->name('acknowledgement');

    /*
    |--------------------------------------------------------------------------
    | PRINT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/print/{id}',
        'print'
    )->name('print');

});


/*
|--------------------------------------------------------------------------
| ITR SERVICES
|--------------------------------------------------------------------------
*/

Route::prefix('itr')
    ->name('itr.')
    ->controller(FileItrController::class)
    ->group(function () {

        Route::get(
            '/',
            'index'
        )->name('index');

        Route::post(
            '/preview',
            'preview'
        )->name('preview');

        Route::get(
            '/preview-page',
            'previewPage'
        )->name('preview-page');

        Route::post(
            '/final-submit',
            'finalSubmit'
        )->name('final-submit');

        Route::get(
            '/acknowledgement/{id}',
            'acknowledgement'
        )->name('acknowledgement');

        Route::get(
            '/history',
            'history'
        )->name('history');

        
        Route::get(
            '/show/{id}',
            'show'
        )->name('show');

        Route::delete(
            '/delete/{id}',
            'destroy'
        )->name('delete');
    });

  /*
|--------------------------------------------------------------------------
| PAN CORRECTION
|--------------------------------------------------------------------------
*/

Route::prefix('pan-correction')

    ->name('pan-correction.')

    ->controller(PanCorrectionController::class)

    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | APPLY
        |--------------------------------------------------------------------------
        */

        Route::get(

            '/apply',

            'create'

        )->name('apply');

        /*
        |--------------------------------------------------------------------------
        | PREVIEW
        |--------------------------------------------------------------------------
        */

        Route::post(

            '/preview',

            'preview'

        )->name('preview');

        /*
        |--------------------------------------------------------------------------
        | PREVIEW PAGE
        |--------------------------------------------------------------------------
        */

        Route::get(

            '/preview-page',

            'previewPage'

        )->name('preview-page');

        /*
        |--------------------------------------------------------------------------
        | STORE
        |--------------------------------------------------------------------------
        */

        Route::post(

            '/store',

            'finalSubmit'

        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | HISTORY
        |--------------------------------------------------------------------------
        */

        Route::get(

            '/history',

            'index'

        )->name('history');

        /*
        |--------------------------------------------------------------------------
        | SHOW
        |--------------------------------------------------------------------------
        */

        Route::get(

            '/show/{id}',

            'show'

        )->name('show');


        Route::get(
            '/receiving/{id}',
            'acknowledgement'
        )->name('receiving');

        /*
        |--------------------------------------------------------------------------
        | PRINT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/print/{id}',
            'print'
        )->name('print');

    });

    
    Route::prefix('pan-apply-without-document')

    ->name('pan-apply-without-document.')

    ->controller(PanApplyWithoutDocumentController::class)

    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | APPLY
        |--------------------------------------------------------------------------
        */

        Route::get(

            '/apply',

            'create'

        )->name('apply');

        /*
        |--------------------------------------------------------------------------
        | PREVIEW
        |--------------------------------------------------------------------------
        */

        Route::post(

            '/preview',

            'preview'

        )->name('preview');

        /*
        |--------------------------------------------------------------------------
        | PREVIEW PAGE
        |--------------------------------------------------------------------------
        */

        Route::get(

            '/preview-page',

            'previewPage'

        )->name('preview-page');

        /*
        |--------------------------------------------------------------------------
        | STORE
        |--------------------------------------------------------------------------
        */

        Route::post(

            '/store',

            'finalSubmit'

        )->name('store');

        /*
        |--------------------------------------------------------------------------
        | HISTORY
        |--------------------------------------------------------------------------
        */

        Route::get(

            '/history',

            'index'

        )->name('history');

        /*
        |--------------------------------------------------------------------------
        | SHOW
        |--------------------------------------------------------------------------
        */

        Route::get(

            '/show/{id}',

            'show'

        )->name('show');


        Route::get(
            '/receiving/{id}',
            'acknowledgement'
        )->name('receiving');

        /*
        |--------------------------------------------------------------------------
        | PRINT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/print/{id}',
            'print'
        )->name('print');

    });


    /*
    |--------------------------------------------------------------------------
    | PAN TRAINING
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/pan/training',

        [PanTrainingController::class,
        'index']

    )->name('pan.training');

    Route::prefix('pan-find')
        ->name('pan-find.')
        ->controller(PanFindController::class)
        ->group(function () {

            Route::get('/', 'create')->name('apply');

            Route::post('/store', 'store')->name('store');

            Route::get('/history', 'history')->name('history');

            Route::get('/show-details/{history}','show')->name('show');
         
        });
    /*
    |--------------------------------------------------------------------------
    | PAN VERIFY
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/pan/verify',

        [PanVerifyController::class,
        'index']

    )->name('pan.verify');

    /*
    |--------------------------------------------------------------------------
    | VERIFICATION SERVICES
    |--------------------------------------------------------------------------
    */

    Route::prefix('verification')
    ->name('verification.')
    ->group(function () {

        Route::get(
            '/bank',
            [BankVerificationController::class, 'index']
        )->name('bank');

        Route::get(
            '/voter',
            [VoterVerificationController::class, 'index']
        )->name('voter');

        Route::get(
            '/rc',
            [RcVerificationController::class, 'index']
        )->name('rc');

        Route::get(
            '/dl',
            [DlVerificationController::class, 'index']
        )->name('dl');

        Route::get(
            '/gst',
            [GstVerificationController::class, 'index']
        )->name('gst');

        Route::get(
            '/passport',
            [PassportVerificationController::class, 'index']
        )->name('passport');

    });

    /*
    |--------------------------------------------------------------------------
    | TOOLS
    |--------------------------------------------------------------------------
    */

    Route::prefix('tools')
    ->name('tools.')
    ->group(function () {

        Route::get(
            '/aadhaar-pvc',
            [AadhaarPvcController::class, 'index']
        )->name('aadhaar.pvc');

        Route::get(
            '/hisab-kitab',
            [HisabKitabController::class, 'index']
        )->name('hisab.kitab');

        Route::get(
            '/file-converter',
            [FileConverterController::class, 'index']
        )->name('file.converter');

        Route::get(
            '/passport-photo',
            [PassportPhotoController::class, 'index']
        )->name('passport.photo');

    });



  

/*
|--------------------------------------------------------------------------
| AADHAAR SERVICES
|--------------------------------------------------------------------------
*/

Route::prefix('aadhaar')
    ->name('aadhaar.')
    ->controller(AadhaarServiceController::class)
    ->group(function () {

        Route::get(
            '/service/{service}',
            'create'
        )->name('service');

        Route::post(
            '/preview',
            'preview'
        )->name('preview');

        Route::get(
            '/preview-page',
            'previewPage'
        )->name('preview-page');

        Route::post(
            '/final-submit',
            'finalSubmit'
        )->name('final-submit');

        Route::get(
            '/history',
            'index'
        )->name('history');

        Route::get(
            '/show/{id}',
            'show'
        )->name('show');

        Route::get(
            '/receiving/{id}',
            'acknowledgement'
        )->name('receiving');

        Route::get(
            '/print/{id}',
            'print'
        )->name('print');

        Route::delete(
            '/delete/{id}',
            'destroy'
        )->name('delete');
    });


    /*
    |--------------------------------------------------------------------------
    | CSC SERVICES
    |--------------------------------------------------------------------------
    */


    Route::prefix('csc')
        ->name('csc.')
        ->controller(CscServiceController::class)
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | SERVICE FORM
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/service/{service}',
                'create'
            )->name('service');

            /*
            |--------------------------------------------------------------------------
            | PREVIEW
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/preview',
                'preview'
            )->name('preview');

            /*
            |--------------------------------------------------------------------------
            | PREVIEW PAGE
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/preview-page',
                'previewPage'
            )->name('preview-page');

            /*
            |--------------------------------------------------------------------------
            | FINAL SUBMIT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/final-submit',
                'finalSubmit'
            )->name('final-submit');

            /*
            |--------------------------------------------------------------------------
            | HISTORY
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/history',
                'index'
            )->name('history');

            /*
            |--------------------------------------------------------------------------
            | SHOW
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/show/{id}',
                'show'
            )->name('show');

            /*
            |--------------------------------------------------------------------------
            | RECEIVING
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/receiving/{id}',
                'acknowledgement'
            )->name('receiving');

            /*
            |--------------------------------------------------------------------------
            | PRINT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/print/{id}',
                'print'
            )->name('print');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                'destroy'
            )->name('delete');
        });


        /*
        |--------------------------------------------------------------------------
        | VOTER ID  SERVICES
        |--------------------------------------------------------------------------
        */


    Route::prefix('voter-id')
        ->name('voter-id.')
        ->controller(VoterIdServiceController::class)
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | SERVICE FORM
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/service/{service}',
                'create'
            )->name('service');

            /*
            |--------------------------------------------------------------------------
            | PREVIEW
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/preview',
                'preview'
            )->name('preview');

            /*
            |--------------------------------------------------------------------------
            | PREVIEW PAGE
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/preview-page',
                'previewPage'
            )->name('preview-page');

            /*
            |--------------------------------------------------------------------------
            | FINAL SUBMIT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/final-submit',
                'finalSubmit'
            )->name('final-submit');

            /*
            |--------------------------------------------------------------------------
            | HISTORY
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/history',
                'index'
            )->name('history');

            /*
            |--------------------------------------------------------------------------
            | SHOW
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/show/{id}',
                'show'
            )->name('show');

            /*
            |--------------------------------------------------------------------------
            | RECEIVING
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/receiving/{id}',
                'acknowledgement'
            )->name('receiving');

            /*
            |--------------------------------------------------------------------------
            | PRINT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/print/{id}',
                'print'
            )->name('print');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                'destroy'
            )->name('delete');
        });

    
    Route::prefix('bank-account')
        ->name('bank-account.')
        ->controller(BankAccountServiceController::class)
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | SERVICE FORM
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/service/{service}',
                'create'
            )->name('service');

            /*
            |--------------------------------------------------------------------------
            | PREVIEW
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/preview',
                'preview'
            )->name('preview');

            /*
            |--------------------------------------------------------------------------
            | PREVIEW PAGE
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/preview-page',
                'previewPage'
            )->name('preview-page');

            /*
            |--------------------------------------------------------------------------
            | FINAL SUBMIT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/final-submit',
                'finalSubmit'
            )->name('final-submit');

            /*
            |--------------------------------------------------------------------------
            | HISTORY
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/history',
                'index'
            )->name('history');

            /*
            |--------------------------------------------------------------------------
            | SHOW
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/show/{id}',
                'show'
            )->name('show');

            /*
            |--------------------------------------------------------------------------
            | RECEIVING
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/receiving/{id}',
                'acknowledgement'
            )->name('receiving');

            /*
            |--------------------------------------------------------------------------
            | PRINT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/print/{id}',
                'print'
            )->name('print');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                'destroy'
            )->name('delete');
        });



        Route::prefix('other-service')
        ->name('other-service.')
        ->controller(OtherServiceController::class)
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | SERVICE FORM
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/service/{service}',
                'create'
            )->name('service');

            /*
            |--------------------------------------------------------------------------
            | PREVIEW
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/preview',
                'preview'
            )->name('preview');

            /*
            |--------------------------------------------------------------------------
            | PREVIEW PAGE
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/preview-page',
                'previewPage'
            )->name('preview-page');

            /*
            |--------------------------------------------------------------------------
            | FINAL SUBMIT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/final-submit',
                'finalSubmit'
            )->name('final-submit');

            /*
            |--------------------------------------------------------------------------
            | HISTORY
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/history',
                'index'
            )->name('history');

            /*
            |--------------------------------------------------------------------------
            | SHOW
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/show/{id}',
                'show'
            )->name('show');

            /*
            |--------------------------------------------------------------------------
            | RECEIVING
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/receiving/{id}',
                'acknowledgement'
            )->name('receiving');

            /*
            |--------------------------------------------------------------------------
            | PRINT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/print/{id}',
                'print'
            )->name('print');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                'destroy'
            )->name('delete');
        });


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post(

        '/logout',

        [LoginController::class,
        'logout']

    )->name('logout');

    Route::get(
        '/idle-logout',
        function () {

            Auth::logout();

            session()->invalidate();

            session()->regenerateToken();

            return redirect()
                ->route('login');

        }
    )->name('logout.idle');

});
