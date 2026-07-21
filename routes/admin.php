<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\NavigationMenuController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeAttendanceController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\SalaryStructureController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\PayslipController;
use App\Http\Controllers\Admin\ServiceGuidelineController;
use App\Http\Controllers\Admin\AdminTdsController;
use App\Http\Controllers\Admin\RetailerActivityController;
use App\Http\Controllers\Admin\PopupAnnouncementController;

use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\UpiController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\AdminNewPanController;
use App\Http\Controllers\Admin\AdminPanCorrectionController;
use App\Http\Controllers\Admin\AdminItrController as ItrFileController;
use App\Http\Controllers\Admin\RetailerApprovalController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ChargeController;
use App\Http\Controllers\Admin\AdminAadhaarController;
use App\Http\Controllers\Admin\AdminCscController;
use App\Http\Controllers\Admin\AdminVoterIdController;
use App\Http\Controllers\Admin\AdminBankAccountController;
use App\Http\Controllers\Admin\AdminOtherServiceController;
use App\Http\Controllers\Admin\BankDocsController;
use App\Http\Controllers\Admin\AdminPanFindController;
use App\Http\Controllers\Admin\ConversationController;


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
|
| Prefix Automatically Applied:
| /admin
|
| Name Automatically Applied:
| admin.
|
*/

Route::middleware([

    'auth',
    

])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard')
     ->middleware('permission:dashboard.view');

     /* ================= PROFILE ================= */

    Route::get(
        '/profile',
        [UserController::class, 'profile']
    )->name('profile')
    ->middleware('permission:profile.view');

    Route::post(
        '/profile/update',
        [UserController::class, 'profileUpdate']
    )->name('profile.update')
    ->middleware('permission:profile.edit');

    Route::post(
        '/profile/password',
        [UserController::class, 'changePassword']
    )->name('profile.password')
    ->middleware('permission:profile.password');



    /*
    |--------------------------------------------------------------------------
    | STATES
    |--------------------------------------------------------------------------
    */

    Route::prefix('states')
        ->name('states.')
        ->middleware('permission:states.view')
        ->group(function () {

        Route::get(
            '/',
            [StateController::class, 'index']
        )->name('index');

        Route::post(
            '/store',
            [StateController::class, 'store']
        )->name('store')
         ->middleware('permission:states.create');

        Route::delete(
            '/delete/{id}',
            [StateController::class, 'destroy']
        )->name('delete')
         ->middleware('permission:states.delete');

        Route::get(
            '/list',
            [StateController::class, 'list']
        )->name('list');

    });

    /*
    |--------------------------------------------------------------------------
    | DISTRICTS
    |--------------------------------------------------------------------------
    */

    Route::prefix('districts')
        ->name('districts.')
        ->middleware('permission:districts.view')
        ->group(function () {

        Route::get(
            '/',
            [DistrictController::class, 'index']
        )->name('index');

        Route::post(
            '/store',
            [DistrictController::class, 'store']
        )->name('store')
         ->middleware('permission:districts.create');

        Route::delete(
            '/delete/{id}',
            [DistrictController::class, 'destroy']
        )->name('delete')
         ->middleware('permission:districts.delete');

        Route::get(
            '/list',
            [DistrictController::class, 'list']
        )->name('list');

        Route::get(
            '/by-state/{stateId}',
            [DistrictController::class, 'getByState']
        )->name('byState');

    });

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    Route::prefix('users')
        ->name('users.')
        ->middleware('permission:users.view')
        ->group(function () {

        Route::get(
            '/',
            [UserController::class, 'index']
        )->name('index');

        Route::get(
            '/create',
            [UserController::class, 'create']
        )->name('create')
         ->middleware('permission:users.create');

        Route::post(
            '/store',
            [UserController::class, 'store']
        )->name('store')
         ->middleware('permission:users.create');

        Route::get(
            '/list',
            [UserController::class, 'list']
        )->name('list');

        Route::get(
            '/edit/{id}',
            [UserController::class, 'edit']
        )->name('edit')
         ->middleware('permission:users.edit');

        Route::post(
            '/update/{id}',
            [UserController::class, 'update']
        )->name('update')
         ->middleware('permission:users.edit');

        Route::delete(
            '/delete/{id}',
            [UserController::class, 'delete']
        )->name('delete')
         ->middleware('permission:users.delete');

        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile',
            [UserController::class, 'profile']
        )->name('profile');

        Route::post(
            '/profile/update',
            [UserController::class, 'profileUpdate']
        )->name('profile.update');

        Route::post(
            '/profile/password',
            [UserController::class, 'changePassword']
        )->name('profile.password');

    });

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */

    Route::prefix('roles')
        ->name('roles.')
        ->middleware('permission:roles.view')
        ->group(function () {

        Route::get(
            '/',
            [RoleController::class, 'index']
        )->name('index');

        Route::get(
            '/create',
            [RoleController::class, 'create']
        )->name('create')
         ->middleware('permission:roles.create');

        Route::post(
            '/store',
            [RoleController::class, 'store']
        )->name('store')
         ->middleware('permission:roles.create');

        Route::get(
            '/edit/{id}',
            [RoleController::class, 'edit']
        )->name('edit')
         ->middleware('permission:roles.edit');

        Route::post(
            '/update/{id}',
            [RoleController::class, 'update']
        )->name('update')
         ->middleware('permission:roles.edit');

        Route::delete(
            '/delete/{id}',
            [RoleController::class, 'destroy']
        )->name('delete')
         ->middleware('permission:roles.delete');

    });

    /*
    |--------------------------------------------------------------------------
    | CMS MODULES
    |--------------------------------------------------------------------------
    */

    /* ================= BANNERS ================= */

    Route::prefix('banners')
        ->name('banners.')
        ->middleware('permission:banners.view')
        ->group(function () {

        Route::get(
            '/',
            [BannerController::class, 'index']
        )->name('index');

        Route::post(
            '/store',
            [BannerController::class, 'store']
        )->name('store')
        ->middleware('permission:banners.create');

        Route::post(
            '/update/{banner}',
            [BannerController::class, 'update']
        )->name('update')
        ->middleware('permission:banners.edit');

        Route::delete(
            '/delete/{banner}',
            [BannerController::class, 'destroy']
        )->name('delete')
        ->middleware('permission:banners.delete');

    });


    /* ================= NAVIGATION ================= */

    Route::prefix('navigation')
        ->name('navigation.')
        ->middleware('permission:navigation.view')
        ->group(function () {

        Route::get(
            '/',
            [NavigationMenuController::class, 'index']
        )->name('index');

        Route::get(
            '/create',
            [NavigationMenuController::class, 'create']
        )->name('create')
        ->middleware('permission:navigation.create');

        Route::post(
            '/store',
            [NavigationMenuController::class, 'store']
        )->name('store')
        ->middleware('permission:navigation.create');

        Route::get(
            '/list',
            [NavigationMenuController::class, 'list']
        )->name('list');

        Route::get(
            '/edit/{id}',
            [NavigationMenuController::class, 'edit']
        )->name('edit')
        ->middleware('permission:navigation.edit');

        Route::post(
            '/update/{id}',
            [NavigationMenuController::class, 'update']
        )->name('update')
        ->middleware('permission:navigation.edit');

        Route::delete(
            '/delete/{id}',
            [NavigationMenuController::class, 'destroy']
        )->name('delete')
        ->middleware('permission:navigation.delete');

    });

    /*
|--------------------------------------------------------------------------
| SERVICE GUIDELINES
|--------------------------------------------------------------------------
*/

    Route::prefix('service-guidelines')
        ->name('service-guidelines.')
        ->middleware('permission:service-guidelines.view')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [ServiceGuidelineController::class, 'index']
            )->name('index');

            /*
            |--------------------------------------------------------------------------
            | LIST (DataTable)
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/list',
                [ServiceGuidelineController::class, 'list']
            )->name('list');

            /*
            |--------------------------------------------------------------------------
            | STORE
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/store',
                [ServiceGuidelineController::class, 'store']
            )->name('store')
            ->middleware('permission:service-guidelines.create');

            /*
            |--------------------------------------------------------------------------
            | EDIT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/edit/{id}',
                [ServiceGuidelineController::class, 'edit']
            )->name('edit')
            ->middleware('permission:service-guidelines.edit');

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/update/{id}',
                [ServiceGuidelineController::class, 'update']
            )->name('update')
            ->middleware('permission:service-guidelines.edit');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                [ServiceGuidelineController::class, 'destroy']
            )->name('destroy')
            ->middleware('permission:service-guidelines.delete');

    });


    /*
    |--------------------------------------------------------------------------
    | BANK FORM DOCS
    |--------------------------------------------------------------------------
    */

    Route::prefix('bank-docs')
        ->name('bank-docs.')
        ->middleware('permission:bank-docs.view')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [BankDocsController::class, 'index']
            )->name('index');

            /*
            |--------------------------------------------------------------------------
            | LIST (DataTable)
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/list',
                [BankDocsController::class, 'list']
            )->name('list');

            /*
            |--------------------------------------------------------------------------
            | STORE
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/store',
                [BankDocsController::class, 'store']
            )->name('store')
            ->middleware('permission:bank-docs.create');

            /*
            |--------------------------------------------------------------------------
            | EDIT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/edit/{id}',
                [BankDocsController::class, 'edit']
            )->name('edit')
            ->middleware('permission:bank-docs.edit');

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/update/{id}',
                [BankDocsController::class, 'update']
            )->name('update')
            ->middleware('permission:bank-docs.edit');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                [BankDocsController::class, 'destroy']
            )->name('destroy')
            ->middleware('permission:bank-docs.delete');

    });


    /* ================= NOTICE ================= */

    Route::prefix('notice')
        ->name('notice.')
        ->middleware('permission:notice.view')
        ->group(function () {

        Route::get(
            '/',
            [NoticeController::class, 'index']
        )->name('index');

        Route::post(
            '/store',
            [NoticeController::class, 'store']
        )->name('store')
        ->middleware('permission:notice.create');

        Route::post(
            '/update/{id}',
            [NoticeController::class, 'update']
        )->name('update')
        ->middleware('permission:notice.edit');

        Route::delete(
            '/delete/{id}',
            [NoticeController::class, 'delete']
        )->name('delete')
        ->middleware('permission:notice.delete');

    });


    /* ================= UPI ================= */

    Route::get(
        '/upi',
        [UpiController::class,'index']
    )->name('upi.index')
    ->middleware('permission:upi.view');

    Route::post(
        '/upi',
        [UpiController::class,'store']
    )->name('upi.store')
    ->middleware('permission:upi.create');

    Route::get(
        '/upi/activate/{id}',
        [UpiController::class,'activate']
    )->name('admin.upi.activate')
    ->middleware('permission:upi.activate');

    Route::post(
        '/upi/update/{id}',
        [UpiController::class,'update']
    )->name('upi.update')
    ->middleware('permission:upi.edit');

    Route::post(
        '/upi/delete/{id}',
        [UpiController::class,'delete']
    )->name('upi.delete')
 ->middleware('permission:upi.delete');


/* ================= PAGES ================= */

Route::prefix('pages')
    ->name('pages.')
    ->middleware('permission:pages.view')
    ->group(function () {

    Route::get(
        '/',
        [PageController::class, 'index']
    )->name('index');

    Route::get(
        '/list',
        [PageController::class, 'list']
    )->name('list');

    Route::post(
        '/store',
        [PageController::class, 'store']
    )->name('store')
     ->middleware('permission:pages.create');

    Route::post(
        '/update/{id}',
        [PageController::class, 'update']
    )->name('update')
     ->middleware('permission:pages.edit');

    Route::delete(
        '/delete/{id}',
        [PageController::class, 'destroy']
    )->name('delete')
     ->middleware('permission:pages.delete');

});


    /* ================= LOGO SETTINGS ================= */

    Route::get(
        '/settings/logo',
        [SettingController::class, 'logoForm']
    )->name('logo.form')
    ->middleware('permission:settings.view');

    Route::post(
        '/settings/logo',
        [SettingController::class, 'saveLogo']
    )->name('logo.save')
    ->middleware('permission:settings.edit');

    Route::delete(
        '/settings/logo',
        [SettingController::class, 'deleteLogo']
    )->name('logo.delete')
    ->middleware('permission:settings.delete');


    /* ================= FOOTER ================= */

    Route::prefix('footer')
        ->name('footer.')
        ->middleware('permission:footer.view')
        ->group(function () {

        Route::get(
            '/',
            [FooterController::class, 'index']
        )->name('index');

        Route::get(
            '/list',
            [FooterController::class, 'list']
        )->name('list');

        Route::post(
            '/store',
            [FooterController::class, 'store']
        )->name('store')
        ->middleware('permission:footer.create');

        Route::post(
            '/update/{id}',
            [FooterController::class, 'update']
        )->name('update')
        ->middleware('permission:footer.edit');

        Route::delete(
            '/delete/{id}',
            [FooterController::class, 'delete']
        )->name('delete')
        ->middleware('permission:footer.delete');

        Route::post(
            '/settings',
            [FooterController::class, 'storeSetting']
        )->name('storeSetting')
        ->middleware('permission:footer.settings');

    });

    
    
    /*
    |--------------------------------------------------------------------------
    | COMMUNICATION
    |--------------------------------------------------------------------------
    */

   
  

    /*
    |--------------------------------------------------------------------------
    | SETTINGS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/settings/logo',
        [SettingController::class, 'logoForm']
    )->name('logo.form');

    Route::post(
        '/settings/logo',
        [SettingController::class, 'saveLogo']
    )->name('logo.save');

    

    /*
    |--------------------------------------------------------------------------
    | WALLET MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::prefix('wallet')
        ->name('wallet.')
        ->middleware('permission:wallet.view')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Wallet List
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [WalletController::class,'index']
            )->name('index');

            /*
            |--------------------------------------------------------------------------
            | Add Balance
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/add/{id}',
                [WalletController::class,'addBalance']
            )->name('add')
            ->middleware('permission:wallet.add');

              /*
        |--------------------------------------------------------------------------
        | DataTable Sources
        |--------------------------------------------------------------------------
        */

        Route::get('/retailers/list', [WalletController::class,'retailerList'])
            ->name('retailers.list');

        Route::get('/executives/list', [WalletController::class,'executiveList'])
            ->name('executives.list');

   
            /*
            |--------------------------------------------------------------------------
            | Deduct / Pull Back Balance
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/deduct/{id}',
                [WalletController::class,'deductBalance']
            )->name('deduct')
            ->middleware('permission:wallet.deduct');

            /*
            |--------------------------------------------------------------------------
            | Wallet Transactions
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/transactions',
                [WalletController::class,'transactions']
            )->name('transactions')
            ->middleware('permission:wallet.transactions');

            /*
            |--------------------------------------------------------------------------
            | Payment Requests
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/payment-requests',
                [WalletController::class,'paymentRequests']
            )->name('payment-requests')
            ->middleware('permission:payment.requests.view');

            /*
            |--------------------------------------------------------------------------
            | Payment Request Details
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/payment-request/{id}',
                [WalletController::class,'showPaymentRequest']
            )->name('payment-request.show')
            ->middleware('permission:payment.requests.view');

            /*
            |--------------------------------------------------------------------------
            | Approve Payment
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/payment-request/{id}/approve',
                [WalletController::class,'approvePayment']
            )->name('payment-request.approve')
            ->middleware('permission:payment.requests.approve');

            /*
            |--------------------------------------------------------------------------
            | Reject Payment
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/payment-request/{id}/reject',
                [WalletController::class,'rejectPayment']
            )->name('payment-request.reject')
            ->middleware('permission:payment.requests.reject');

        });

    /*
    |--------------------------------------------------------------------------
    | NEW PAN MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('pan')
        ->name('pan.')
        ->middleware('permission:pan.view')
        ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | PAN APPLICATION LIST
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [AdminNewPanController::class, 'index']
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | PAN APPLICATION SHOW
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/show/{id}',
            [AdminNewPanController::class, 'show']
        )->name('show');

        /*
        |--------------------------------------------------------------------------
        | ASSIGN APPLICATION
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/assign/{id}',
            [AdminNewPanController::class, 'assign']
        )->name('assign');

    

        Route::post(
            '/pan/document-upload/{id}',
            [AdminNewPanController::class, 'uploadDocument']
        )->name('document.upload');

        Route::get(

                '/pan/{id}/download-documents',

                [AdminNewPanController::class, 'downloadDocuments']

            )->name('new.download.documents');

        Route::post(
            '/pan/{id}/reject',
            [AdminNewPanController::class, 'reject']
        )->name('reject');

    });


      /*  |--------------------------------------------------------------------------
    |  PAN CORRECTION MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('pan-correction')
        ->name('pan-correction.')
        ->middleware('permission:pan.view')
        ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | PAN APPLICATION LIST
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [AdminPanCorrectionController::class, 'index']
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | PAN APPLICATION SHOW
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/show/{id}',
            [AdminPanCorrectionController::class, 'show']
        )->name('show');

        /*
        |--------------------------------------------------------------------------
        | ASSIGN APPLICATION
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/assign/{id}',
            [AdminPanCorrectionController::class, 'assign']
        )->name('assign');

    

        Route::post(
            '/pan/document-upload/{id}',
            [AdminPanCorrectionController::class, 'uploadDocument']
        )->name('document.upload');

        Route::get(

                '/pan-correction/{id}/download-documents',

                [AdminPanCorrectionController::class, 'downloadDocuments']

            )->name('new.download.documents');

         Route::post(
            '/pan-correction/{id}/reject',
            [AdminPanCorrectionController::class, 'reject']
        )->name('reject');



    });


    /*
    |--------------------------------------------------------------------------
    | PAN FIND MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('pan-find')
        ->name('pan-find.')
        ->middleware('permission:pan-find.view')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | LIST
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [AdminPanFindController::class, 'index']
            )->name('index');

            /*
            |--------------------------------------------------------------------------
            | SHOW
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/show/{id}',
                [AdminPanFindController::class, 'show']
            )->name('show');

            /*
            |--------------------------------------------------------------------------
            | ASSIGN
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/assign/{id}',
                [AdminPanFindController::class, 'assign']
            )->name('assign')
            ->middleware('permission:pan-find.assign');

            /*
            |--------------------------------------------------------------------------
            | UPLOAD DOCUMENT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/document-upload/{id}',
                [AdminPanFindController::class, 'uploadDocument']
            )->name('document.upload');

            /*
            |--------------------------------------------------------------------------
            | DOWNLOAD DOCUMENTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/download-documents/{id}',
                [AdminPanFindController::class, 'downloadDocuments']
            )->name('download.documents');

            /*
            |--------------------------------------------------------------------------
            | REJECT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/reject/{id}',
                [AdminPanFindController::class, 'reject']
            )->name('reject')
            ->middleware('permission:pan-find.reject');

        });



    /*
    |--------------------------------------------------------------------------
    | AADHAAR MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('aadhaar')
        ->name('aadhaar.')
        ->middleware('permission:aadhaar.view')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | LIST
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [AdminAadhaarController::class, 'index']
            )->name('index');

            /*
            |--------------------------------------------------------------------------
            | SHOW
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/show/{id}',
                [AdminAadhaarController::class, 'show']
            )->name('show');

            /*
            |--------------------------------------------------------------------------
            | ASSIGN
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/assign/{id}',
                [AdminAadhaarController::class, 'assign']
            )->name('assign');

            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/status/{id}',
                [AdminAadhaarController::class, 'status']
            )->name('status');

            /*
            |--------------------------------------------------------------------------
            | UPLOAD DOCUMENT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/document-upload/{id}',
                [AdminAadhaarController::class, 'uploadDocument']
            )->name('document.upload');

            /*
            |--------------------------------------------------------------------------
            | DOWNLOAD DOCUMENTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/download-documents/{id}',
                [AdminAadhaarController::class, 'downloadDocuments']
            )->name('download.documents');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                [AdminAadhaarController::class, 'delete']
            )->name('delete');

             Route::post(
                '/rejected/{id}/reject',
                [AdminAadhaarController::class, 'reject']
            )->name('reject');

        });



     /*
    |--------------------------------------------------------------------------
    | CSC MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('csc')
        ->name('csc.')
        ->middleware('permission:csc.view')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | LIST
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [AdminCscController::class, 'index']
            )->name('index');

            /*
            |--------------------------------------------------------------------------
            | SHOW
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/show/{id}',
                [AdminCscController::class, 'show']
            )->name('show');

            /*
            |--------------------------------------------------------------------------
            | ASSIGN
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/assign/{id}',
                [AdminCscController::class, 'assign']
            )->name('assign');

            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/status/{id}',
                [AdminCscController::class, 'status']
            )->name('status');

            /*
            |--------------------------------------------------------------------------
            | UPLOAD DOCUMENT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/document-upload/{id}',
                [AdminCscController::class, 'uploadDocument']
            )->name('document.upload');

            /*
            |--------------------------------------------------------------------------
            | DOWNLOAD DOCUMENTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/download-documents/{id}',
                [AdminCscController::class, 'downloadDocuments']
            )->name('download.documents');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                [AdminCscController::class, 'delete']
            )->name('delete');

             Route::post(
                '/rejected/{id}/reject',
                [AdminCscController::class, 'reject']
            )->name('reject');

        });


     Route::prefix('voter-id')
        ->name('voter-id.')
        ->middleware('permission:voter-id.view')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | LIST
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [AdminVoterIdController::class, 'index']
            )->name('index');

            /*
            |--------------------------------------------------------------------------
            | SHOW
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/show/{id}',
                [AdminVoterIdController::class, 'show']
            )->name('show');

            /*
            |--------------------------------------------------------------------------
            | ASSIGN
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/assign/{id}',
                [AdminVoterIdController::class, 'assign']
            )->name('assign');

            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/status/{id}',
                [AdminVoterIdController::class, 'status']
            )->name('status');

            /*
            |--------------------------------------------------------------------------
            | UPLOAD DOCUMENT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/document-upload/{id}',
                [AdminVoterIdController::class, 'uploadDocument']
            )->name('document.upload');

            /*
            |--------------------------------------------------------------------------
            | DOWNLOAD DOCUMENTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/download-documents/{id}',
                [AdminVoterIdController::class, 'downloadDocuments']
            )->name('download.documents');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                [AdminVoterIdController::class, 'delete']
            )->name('delete');

             Route::post(
                '/rejected/{id}/reject',
                [AdminVoterIdController::class, 'reject']
            )->name('reject');

        });


     /*
    |--------------------------------------------------------------------------
    | BANK-ACCOUNT MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('bank-account')
        ->name('bank-account.')
        ->middleware('permission:bank-account.view')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | LIST
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [AdminBankAccountController::class, 'index']
            )->name('index');

            /*
            |--------------------------------------------------------------------------
            | SHOW
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/show/{id}',
                [AdminBankAccountController::class, 'show']
            )->name('show');

            /*
            |--------------------------------------------------------------------------
            | ASSIGN
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/assign/{id}',
                [AdminBankAccountController::class, 'assign']
            )->name('assign');

            /*
            |--------------------------------------------------------------------------
            | UPDATE STATUS
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/status/{id}',
                [AdminBankAccountController::class, 'status']
            )->name('status');

            /*
            |--------------------------------------------------------------------------
            | UPLOAD DOCUMENT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/document-upload/{id}',
                [AdminBankAccountController::class, 'uploadDocument']
            )->name('document.upload');

            /*
            |--------------------------------------------------------------------------
            | DOWNLOAD DOCUMENTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/download-documents/{id}',
                [AdminBankAccountController::class, 'downloadDocuments']
            )->name('download.documents');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                [AdminBankAccountController::class, 'delete']
            )->name('delete');

             Route::post(
                '/rejected/{id}/reject',
                [AdminBankAccountController::class, 'reject']
            )->name('reject');

        });

     /*
    |--------------------------------------------------------------------------
    | OTHER SERVICE MODULE
    |--------------------------------------------------------------------------
    */

    Route::prefix('other-service')
        ->name('other-service.')
        ->middleware('permission:other-service.view')
        ->group(function () {

            Route::get(
                '/',
                [AdminOtherServiceController::class, 'index']
            )->name('index');

            Route::get(
                '/finance',
                [AdminOtherServiceController::class, 'financeIndex']
            )->name('finance.index');

            Route::get(
                '/show/{id}',
                [AdminOtherServiceController::class, 'show'
            ])->name('show');

            Route::post(
                '/assign/{id}',
                [AdminOtherServiceController::class, 'assign']
            )->name('assign');

            Route::post(
                '/status/{id}',
                [AdminOtherServiceController::class, 'status']
            )->name('status');

            Route::post(
                '/document-upload/{id}',
                [AdminOtherServiceController::class, 'uploadDocument']
            )->name('document.upload');

            Route::get(
                '/download-documents/{id}',
                [AdminOtherServiceController::class, 'downloadDocuments']
            )->name('download.documents');

            Route::delete(
                '/delete/{id}',
                [AdminOtherServiceController::class, 'delete']
            )->name('delete');

            Route::post(
                '/rejected/{id}/reject',
                [AdminOtherServiceController::class, 'reject']
            )->name('reject');
        });





    /*
    |--------------------------------------------------------------------------
    | ITR MODULE
    |--------------------------------------------------------------------------
    |
    */

    Route::prefix('itr')
        ->name('itr.')
        ->middleware([

            'auth',
        
            'permission:itr.view'

        ])
        ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ITR DASHBOARD / LIST
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [ItrFileController::class, 'index']
        )->name('index');



        /*
        |--------------------------------------------------------------------------
        | ITR HISTORY
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/history',
            [ItrFileController::class, 'history']
        )->name('history');



        /*
        |--------------------------------------------------------------------------
        | SHOW ITR DETAILS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/show/{id}',
            [ItrFileController::class, 'show']
        )->name('show');



        /*
        |--------------------------------------------------------------------------
        | ASSIGN ITR
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/assign/{id}',
            [ItrFileController::class, 'assign']
        )->name('assign');



        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/status/{id}',
            [ItrFileController::class, 'status']
        )->name('status');


        Route::post(
            '/document-upload/{id}',
            [ItrFileController::class, 'uploadDocument']
        )->name('document.upload');


        /*
        |--------------------------------------------------------------------------
        | DELETE ITR
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/delete/{id}',
            [ItrFileController::class, 'delete']
        )->name('delete');

        /*
            |--------------------------------------------------------------------------
            | DOWNLOAD ALL DOCUMENTS
            |--------------------------------------------------------------------------
            */

            Route::get(

                '/itr/{id}/download-documents',

                [ItrFileController::class, 'downloadDocuments']

            )->name('download.documents');


              Route::post(
                '/rejected/{id}/reject',
                [ItrFileController::class, 'reject']
            )->name('reject');


    });


    Route::prefix('tds')
        ->name('tds.')
        ->middleware([

            'auth',
        
            'permission:tds.view'

        ])
        ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ITR DASHBOARD / LIST
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [AdminTdsController::class, 'index']
        )->name('index');



        /*
        |--------------------------------------------------------------------------
        | ITR HISTORY
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/history',
            [AdminTdsController::class, 'history']
        )->name('history');



        /*
        |--------------------------------------------------------------------------
        | SHOW ITR DETAILS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/show/{id}',
            [AdminTdsController::class, 'show']
        )->name('show');



        /*
        |--------------------------------------------------------------------------
        | ASSIGN ITR
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/assign/{id}',
            [AdminTdsController::class, 'assign']
        )->name('assign');



        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/status/{id}',
            [AdminTdsController::class, 'status']
        )->name('status');


        Route::post(
            '/document-upload/{id}',
            [AdminTdsController::class, 'uploadDocument']
        )->name('document.upload');


        /*
        |--------------------------------------------------------------------------
        | DELETE ITR
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/delete/{id}',
            [AdminTdsController::class, 'delete']
        )->name('delete');

        /*
            |--------------------------------------------------------------------------
            | DOWNLOAD ALL DOCUMENTS
            |--------------------------------------------------------------------------
            */

            Route::get(

                '/tds/{id}/download-documents',

                [AdminTdsController::class, 'downloadDocuments']

            )->name('download.documents');


              Route::post(
                '/rejected/{id}/reject',
                [AdminTdsController::class, 'reject']
            )->name('reject');


    });





        Route::prefix('retailer-approvals')
            ->name('retailer-approvals.')
            ->group(function () {

            Route::get(
                '/',
                [RetailerApprovalController::class, 'index']
            )->name('index');

            Route::post(
                '/approve/{id}',
                [RetailerApprovalController::class, 'approve']
            )->name('approve');

            Route::post(
                '/reject/{id}',
                [RetailerApprovalController::class, 'reject']
            )->name('reject');

            /*
            |--------------------------------------------------------------------------
            | LOGIN AS RETAILER
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/login-as/{userId}',
                [RetailerApprovalController::class, 'loginAsRetailer']
            )->name('login-as');

            /*
            |--------------------------------------------------------------------------
            | BACK TO ADMIN
            |--------------------------------------------------------------------------
            */

            Route::get(
                        '/back-to-admin',
                        [RetailerApprovalController::class, 'backToAdmin']
                    )->name('back-to-admin');

                    Route::get(
                '/modules/{user}',
                [RetailerApprovalController::class, 'modules']
            )->name(
                'modules'
            );

            Route::post(
                '/modules/{user}',
                [RetailerApprovalController::class, 'updateModules']
            )->name(
                'modules.update'

            );

            Route::get(
                    '/retailer-modules/{userId}',
                    [RetailerApprovalController::class, 'getModules']
                )->name(
                    'get-modules'
                );


            });


        Route::prefix('modules')
        ->name('modules.')
        ->middleware('permission:modules.view')
        ->group(function () {

            Route::get(
                '/',
                [ModuleController::class, 'index']
            )->name('index');

            Route::get(
                '/list',
                [ModuleController::class, 'list']
            )->name('list');

            Route::get(
                '/create',
                [ModuleController::class, 'create']
            )->name('create')
            ->middleware('permission:modules.create');

            Route::post(
                '/store',
                [ModuleController::class, 'store']
            )->name('store')
            ->middleware('permission:modules.create');

            Route::get(
                '/edit/{id}',
                [ModuleController::class, 'edit']
            )->name('edit')
            ->middleware('permission:modules.edit');

            Route::post(
                '/update/{id}',
                [ModuleController::class, 'update']
            )->name('update')
            ->middleware('permission:modules.edit');

            Route::delete(
                '/delete/{id}',
                [ModuleController::class, 'destroy']
            )->name('delete')
            ->middleware('permission:modules.delete');

        });


   
    Route::prefix('charges')
        ->name('charges.')
        ->middleware('permission:charges.view')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/',
                [ChargeController::class, 'index']
            )->name('index');

            /*
            |--------------------------------------------------------------------------
            | DATATABLE AJAX
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/list',
                [ChargeController::class, 'list']
            )->name('list');

            /*
            |--------------------------------------------------------------------------
            | STORE
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/store',
                [ChargeController::class, 'store']
            )->name('store')
            ->middleware('permission:charges.create');

            /*
            |--------------------------------------------------------------------------
            | EDIT DATA AJAX
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/edit/{id}',
                [ChargeController::class, 'edit']
            )->name('edit')
            ->middleware('permission:charges.edit');

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/update/{id}',
                [ChargeController::class, 'update']
            )->name('update')
            ->middleware('permission:charges.edit');

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            Route::delete(
                '/delete/{id}',
                [ChargeController::class, 'destroy']
            )->name('delete')
            ->middleware('permission:charges.delete');

        });

        Route::prefix('retailer-activity')
        ->name('retailer-activity.')
        ->controller(RetailerActivityController::class)
        ->group(function () {

            Route::get('/', 'index')->name('index');

            Route::get('/datatable', 'datatable')
                ->name('datatable');
        });



       

        Route::prefix('chat')
        ->name('chat.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Conversation
            |--------------------------------------------------------------------------
            */

            Route::get('/', [ConversationController::class, 'index'])
                ->name('index');

            Route::get('/{conversation}', [ConversationController::class, 'show'])
                ->name('show');

            Route::post('/{conversation}/assign', [ConversationController::class, 'assign'])
                ->name('assign');

            Route::post('/{conversation}/close', [ConversationController::class, 'close'])
                ->name('close');

            Route::post('/{conversation}/reopen', [ConversationController::class, 'reopen'])
                ->name('reopen');

            /*
            |--------------------------------------------------------------------------
            | Messages
            |--------------------------------------------------------------------------
            */

            Route::get('/{conversation}/messages', [MessageController::class, 'index'])
                ->name('messages');

            Route::post('/{conversation}/send', [MessageController::class, 'store'])
                ->name('send');

            Route::post('/{conversation}/read', [MessageController::class, 'markAsRead'])
                ->name('read');

            Route::delete('/messages/{message}', [MessageController::class, 'destroy'])
                ->name('delete');

        });


        Route::prefix('popup-announcements')
    ->name('popup-announcements.')
    ->middleware('permission:popup-announcements.view')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | INDEX
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [PopupAnnouncementController::class, 'index']
        )->name('index');

        /*
        |--------------------------------------------------------------------------
        | DATATABLE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/list',
            [PopupAnnouncementController::class, 'list']
        )->name('list');

        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/create',
            [PopupAnnouncementController::class, 'create']
        )->name('create')
        ->middleware('permission:popup-announcements.create');

        /*
        |--------------------------------------------------------------------------
        | STORE
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/store',
            [PopupAnnouncementController::class, 'store']
        )->name('store')
        ->middleware('permission:popup-announcements.create');

        /*
        |--------------------------------------------------------------------------
        | EDIT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/edit/{id}',
            [PopupAnnouncementController::class, 'edit']
        )->name('edit')
        ->middleware('permission:popup-announcements.edit');

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/update/{id}',
            [PopupAnnouncementController::class, 'update']
        )->name('update')
        ->middleware('permission:popup-announcements.edit');

        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/delete/{id}',
            [PopupAnnouncementController::class, 'destroy']
        )->name('delete')
        ->middleware('permission:popup-announcements.delete');

    });

        

       


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