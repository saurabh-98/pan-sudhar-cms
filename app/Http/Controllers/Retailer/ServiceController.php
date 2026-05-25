<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | APPLY NEW PAN
    |--------------------------------------------------------------------------
    */

    public function applyNewPan()
    {
        return view(
            'retailer.services.apply-new-pan'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAN CORRECTION
    |--------------------------------------------------------------------------
    */

    public function panCorrection()
    {
        return view(
            'retailer.services.pan-correction'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COMPANY PAN
    |--------------------------------------------------------------------------
    */

    public function companyPan()
    {
        return view(
            'retailer.services.company-pan'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAN TRAINING
    |--------------------------------------------------------------------------
    */

    public function panTraining()
    {
        return view(
            'retailer.services.pan-training'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAN FIND
    |--------------------------------------------------------------------------
    */

    public function panFind()
    {
        return view(
            'retailer.verification.pan-find'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAN VERIFY
    |--------------------------------------------------------------------------
    */

    public function panVerify()
    {
        return view(
            'retailer.verification.pan-verify'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BANK VERIFY
    |--------------------------------------------------------------------------
    */

    public function bankVerify()
    {
        return view(
            'retailer.verification.bank-verify'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VOTER VERIFY
    |--------------------------------------------------------------------------
    */

    public function voterVerify()
    {
        return view(
            'retailer.verification.voter-verify'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RC VERIFY
    |--------------------------------------------------------------------------
    */

    public function rcVerify()
    {
        return view(
            'retailer.verification.rc-verify'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DL VERIFY
    |--------------------------------------------------------------------------
    */

    public function dlVerify()
    {
        return view(
            'retailer.verification.dl-verify'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GST VERIFY
    |--------------------------------------------------------------------------
    */

    public function gstVerify()
    {
        return view(
            'retailer.verification.gst-verify'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PASSPORT VERIFY
    |--------------------------------------------------------------------------
    */

    public function passportVerify()
    {
        return view(
            'retailer.verification.passport-verify'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AADHAAR PVC
    |--------------------------------------------------------------------------
    */

    public function aadhaarPvc()
    {
        return view(
            'retailer.utilities.aadhaar-pvc'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HISAB KITAB
    |--------------------------------------------------------------------------
    */

    public function hisabKitab()
    {
        return view(
            'retailer.utilities.hisab-kitab'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PASSPORT PHOTO MAKER
    |--------------------------------------------------------------------------
    */

    public function passportPhotoMaker()
    {
        return view(
            'retailer.utilities.passport-photo-maker'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FILE CONVERTER
    |--------------------------------------------------------------------------
    */

    public function fileConverter()
    {
        return view(
            'retailer.utilities.file-converter'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE SHOPPING
    |--------------------------------------------------------------------------
    */

    public function onlineShopping()
    {
        return view(
            'retailer.utilities.online-shopping'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WALLET
    |--------------------------------------------------------------------------
    */

    public function wallet()
    {
        return view(
            'retailer.wallet.index'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REPORTS
    |--------------------------------------------------------------------------
    */

    public function reports()
    {
        return view(
            'retailer.reports.index'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SETTINGS
    |--------------------------------------------------------------------------
    */

    public function settings()
    {
        return view(
            'retailer.settings'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        return view(
            'retailer.profile'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMERS
    |--------------------------------------------------------------------------
    */

    public function customers()
    {
        return view(
            'retailer.customers.index'
        );
    }
}