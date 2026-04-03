<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

/* ADMIN CONTROLLERS */
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\NavigationMenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\CustomerController;
/* NEW CMS CONTROLLERS */
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\PopupOfferController;
use App\Http\Controllers\Admin\UpiController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\staffOrderController;
use App\Http\Controllers\Staff\staffProfileController;     

/* PUBLIC NEWS */
use App\Http\Controllers\NewsController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

/* HOME */
Route::get('/', [HomeController::class, 'index'])->name('home');

/* MENU */
Route::get('/menu', [HomeController::class, 'menu'])->name('menu');

Route::get('/menu/{id}', [HomeController::class, 'menuDetail'])->name('menu.detail');

/* ORDERS */
Route::get('/orders', [HomeController::class, 'orders'])->name('orders');

/* TRACK ORDER */
Route::get('/track-order', [HomeController::class, 'track'])->name('track');

/* RESERVATION  PROTECTED */
Route::middleware('auth')->group(function () {

    Route::get('/reservation', [HomeController::class, 'reservation'])->name('reservation');

    Route::post('/reservation', [HomeController::class, 'storeReserveTable'])
        ->name('post.reservation');

    Route::get('/available-slots', [HomeController::class, 'availableSlots']);

});

/* CMS PAGE */
Route::get('/page/{slug}', [HomeController::class, 'page'])->name('page.show');

/* NEWS PUBLIC */
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// GUEST ONLY (not logged in)
Route::middleware('guest')->group(function () {

    /* LOGIN */
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    /* REGISTER */
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    /* FORGOT PASSWORD */
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

    /* RESET PASSWORD */
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});


/* LOGOUT (ONLY AUTH USERS) */
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/

/* =========================
   PUBLIC CART (FOR UI)
========================= */


/* =========================
   AUTH REQUIRED
========================= */
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('user.home');
    Route::get('/cart/list', [CartController::class, 'index'])->name('cart.index');


    /* CART PAGE */
    Route::get('/cart', [CartController::class, 'view'])->name('cart.page');
    Route::get('/cart/count', [CartController::class, 'count']);
    /* CART ACTIONS */
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update'); // 🔥 ADD THIS

    /* CHECKOUT */
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout'); // 🔥 ADD
    Route::post('/checkout/calculate', [CheckoutController::class, 'calculate'])->name('calculate'); // 🔥 ADD

    Route::post('/cart/place-order', [CartController::class, 'placeOrder'])->name('cart.place.order');

    /* OFFERS */
    Route::post('/cart/apply-offer', [CartController::class, 'applyOffer'])->name('cart.apply.offer');

});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

    /* ================= DASHBOARD ================= */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | MANAGEMENT
    |--------------------------------------------------------------------------
    */

    /* CATEGORY */
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::get('/list', [CategoryController::class, 'list'])->name('list');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    });

    /* MENU */
    Route::prefix('menus')->name('menus.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('/create', [MenuController::class, 'create'])->name('create');
        Route::get('/list', [MenuController::class, 'list'])->name('list');
        Route::post('/store', [MenuController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MenuController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [MenuController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MenuController::class, 'delete'])->name('delete');
    });

    /* USERS */
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/list', [UserController::class, 'list'])->name('list');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete');
        Route::get('/customers', [UserController::class, 'customers']) ->name('customers.index');
        Route::get('/show/{id}', [UserController::class, 'customerShow'])->name('customer.show');
        Route::get('/customer-list', [UserController::class, 'customerList'])->name('customer.list');
        Route::get('/customer-orders/{id}', [UserController::class, 'customerOrders'])->name('customer.orders');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('customer.delete');
    });

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'profileUpdate'])->name('profile.update');

    Route::post('/profile/password', [UserController::class, 'changePassword'])->name('profile.password');

    /* TABLE MANAGEMENT */
    Route::prefix('tables')->name('tables.')->group(function () {

        Route::get('/', [TableController::class,'index'])->name('index');
        Route::get('/list', [TableController::class,'list'])->name('list');

        Route::post('/store', [TableController::class,'store'])->name('store');
        Route::post('/update/{id}', [TableController::class,'update'])->name('update');
        Route::post('/delete/{id}', [TableController::class,'delete'])->name('delete');
        Route::post('/toggle/{id}', [TableController::class,'toggle'])->name('toggle');

    });

    /* RESERVATIONS */
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class,'index'])->name('index');
        Route::get('/list', [ReservationController::class,'list'])->name('list');
        Route::post('/store', [ReservationController::class,'store'])->name('store');
        Route::post('/status/{id}', [ReservationController::class,'updateStatus'])->name('status');
        Route::delete('/delete/{id}', [ReservationController::class,'destroy'])->name('delete');
    });

    /* ORDERS */
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/list', [OrderController::class, 'list'])->name('list');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/payment/{id}', [OrderController::class, 'updatePayment'])->name('payment');


        // EXISTING
        Route::post('/status/{id}', [OrderController::class, 'updateStatus'])->name('status');
        Route::get('/invoice/{id}', [OrderController::class, 'invoice'])->name('invoice');
       

        // 🔥 NEW: DOWNLOAD INVOICE
        Route::get('/invoice/{id}/download', [OrderController::class, 'downloadInvoice'])->name('invoice.download');
    });

    /* 🔥 NEW MODULE: ORDER TRACKING */
    Route::get('/order-tracking', [OrderController::class, 'tracking'])->name('order.tracking');

    /* 🔥 NEW MODULE: INVOICES LIST */
    Route::get('/invoices', [OrderController::class, 'invoices'])->name('invoices.index');
    Route::get('/invoice-list', [OrderController::class, 'invoiceList'])->name('orders.invoice.list');

    /* 🔥 NEW MODULE: SETTINGS */
    Route::get('/settings', fn () => view('admin.settings'))->name('settings');

    /* OFFERS */
    Route::prefix('offers')->name('offers.')->group(function () {
        Route::get('/', [OfferController::class, 'index'])->name('index');
        Route::post('/store', [OfferController::class, 'store'])->name('store');
        Route::post('/update/{id}', [OfferController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [OfferController::class, 'delete'])->name('delete');
        Route::get('/list', [OfferController::class, 'list'])->name('list');
    });

    /*
    |--------------------------------------------------------------------------
    | CMS MODULES
    |--------------------------------------------------------------------------
    */

    Route::prefix('banners')->name('banners.')->group(function () {
        Route::get('/', [BannerController::class, 'index'])->name('index');
        Route::post('/store', [BannerController::class, 'store'])->name('store');
        Route::post('/update/{banner}', [BannerController::class, 'update'])->name('update');
        Route::delete('/delete/{banner}', [BannerController::class, 'destroy'])->name('delete');
    });

    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::post('/store', [CampaignController::class, 'store'])->name('store');
        Route::post('/update/{campaign}', [CampaignController::class, 'update'])->name('update');
        Route::delete('/delete/{campaign}', [CampaignController::class, 'destroy'])->name('delete');
    });

    Route::prefix('features')->name('features.')->group(function () {
        Route::get('/', [FeatureController::class, 'index'])->name('index');
        Route::post('/store', [FeatureController::class, 'store'])->name('store');
        Route::post('/update/{feature}', [FeatureController::class, 'update'])->name('update');
        Route::delete('/delete/{feature}', [FeatureController::class, 'destroy'])->name('delete');
    });

    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [AdminNewsController::class, 'index'])->name('index');
        Route::post('/store', [AdminNewsController::class, 'store'])->name('store');
        Route::post('/update/{news}', [AdminNewsController::class, 'update'])->name('update');
        Route::delete('/delete/{news}', [AdminNewsController::class, 'destroy'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | CMS EXISTING
    |--------------------------------------------------------------------------
    */

    Route::prefix('navigation')->name('navigation.')->group(function () {
        Route::get('/', [NavigationMenuController::class, 'index'])->name('index');
        Route::get('/create', [NavigationMenuController::class, 'create'])->name('create');
        Route::post('/store', [NavigationMenuController::class, 'store'])->name('store');
        Route::get('/list', [NavigationMenuController::class, 'list'])->name('list');
        Route::get('/edit/{id}', [NavigationMenuController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [NavigationMenuController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [NavigationMenuController::class, 'destroy'])->name('delete');
    });

    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/list', [PageController::class, 'list'])->name('list');
        Route::post('/store', [PageController::class, 'store'])->name('store');
        Route::post('/update/{id}', [PageController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PageController::class, 'destroy'])->name('delete');
    });

    Route::get('/popup', [PopupOfferController::class, 'index'])->name('popup.index');
    Route::get('/popup/list', [PopupOfferController::class, 'list'])->name('popup.list');
    Route::post('/popup', [PopupOfferController::class, 'store'])->name('popup.store');
    Route::post('/popup/{id}', [PopupOfferController::class, 'update'])->name('popup.update');
    Route::delete('/popup/{id}', [PopupOfferController::class, 'delete'])->name('popup.delete');
    Route::get('/upi', [UpiController::class,'index'])->name('upi.index');
    Route::post('/upi', [UpiController::class,'store'])->name('upi.store');
    Route::get('/upi/activate/{id}', [UpiController::class,'activate'])->name('admin.upi.activate');
    Route::post('/upi/update/{id}', [UpiController::class,'update']);
    Route::post('/upi/delete/{id}', [UpiController::class,'delete']);



     Route::get('/settings/logo', [SettingController::class, 'logoForm'])->name('logo.form');
     Route::post('/settings/logo', [SettingController::class, 'saveLogo'])->name('logo.save');
     Route::delete('/settings/logo', [SettingController::class, 'deleteLogo'])->name('logo.delete');

    Route::prefix('footer')->name('footer.')->group(function () {
        Route::get('/', [FooterController::class, 'index'])->name('index');
        Route::get('/list', [FooterController::class, 'list'])->name('list');
        Route::post('/store', [FooterController::class, 'store'])->name('store');
        Route::post('/update/{id}', [FooterController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [FooterController::class, 'delete'])->name('delete');
        Route::post('/settings', [FooterController::class, 'storeSetting'])->name('storeSetting');
    });

    /*
    |--------------------------------------------------------------------------
    | COMMUNICATION
    |--------------------------------------------------------------------------
    */

    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', fn () => view('admin.contacts.index'))->name('index');
    });

    Route::prefix('newsletters')->name('newsletters.')->group(function () {
        Route::get('/', fn () => view('admin.newsletters.index'))->name('index');
    });

});

/*
|--------------------------------------------------------------------------
| STAFF ROUTES
|--------------------------------------------------------------------------
*/


    Route::prefix('staff')
    ->name('staff.')
    ->middleware(['auth', 'staff'])
    ->group(function () {

    Route::get('/dashboard', [StaffDashboardController::class, 'staffDashboard'])
        ->name('dashboard');

    /* ================= ORDERS ================= */
   

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [StaffOrderController::class, 'index'])->name('index');
        Route::get('/list', [StaffOrderController::class, 'list'])->name('list');
        Route::get('/orders/{id}', [StaffOrderController::class, 'show'])->name('show');
        Route::post('/payment/{id}', [StaffOrderController::class, 'updatePayment'])->name('payment');
        Route::post('/status/{id}', [StaffOrderController::class, 'updateStatus'])->name('status');
        Route::get('/invoice/{id}', [StaffOrderController::class, 'invoices'])->name('invoice');
        Route::get('/invoice-list', [OrderController::class, 'invoiceList'])->name('invoice.list');
        Route::get('/invoice/{id}/download', [OrderController::class, 'downloadInvoice'])->name('invoice.download');
    });


    /* ================= EXTRA ================= */

    Route::get('/order-tracking', [StaffOrderController::class, 'tracking'])
        ->name('order.tracking');

    Route::get('/invoices', [StaffOrderController::class, 'invoices'])
        ->name('invoices.index');

    Route::get('/invoice-list', [StaffOrderController::class, 'invoiceList'])
        ->name('invoice.list');

    Route::get('/profile', [staffProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [staffProfileController::class, 'profileUpdate'])->name('profile.update');

    Route::post('/profile/password', [staffProfileController::class, 'changePassword'])->name('profile.password');


});

Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth','customer']) // 🔐 IMPORTANT
    ->group(function () {

    /* DASHBOARD */
    Route::get('/dashboard', [CustomerController::class,'dashboard'])->name('dashboard');

    /* =========================
       ORDERS
    ========================= */
    Route::get('/orders', [CustomerController::class,'orders'])->name('orders');
    Route::get('/orders/list', [CustomerController::class,'orderList'])->name('orders.list');
   
    // 
    Route::get('/orders/{id}', [CustomerController::class,'orderDetails'])->name('orders.details');
    Route::post('/orders/cancel/{id}', [CustomerController::class,'cancelOrder'])->name('orders.cancel');
    Route::get('/orders/invoice/{id}', [OrderController::class, 'downloadInvoice'])
    ->name('orders.invoice');

    /* =========================
       RESERVATIONS
    ========================= */
    Route::get('/reservations', [CustomerController::class,'reservations'])->name('reservations');
    Route::get('/reservations/list', [CustomerController::class,'reservationList'])->name('reservations.list');

    Route::post('/reservation/cancel/{id}', [CustomerController::class,'cancelReservation'])->name('reservation.cancel');

    /* =========================
       PROFILE
    ========================= */
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');

    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');


});