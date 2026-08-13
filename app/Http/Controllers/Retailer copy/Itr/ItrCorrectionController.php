<?php

namespace App\Http\Controllers\Retailer\Itr;

use App\Http\Controllers\Controller;

class ItrCorrectionController extends Controller
{
    public function index()
    {
        return view('retailer.itr.correction');
    }
}