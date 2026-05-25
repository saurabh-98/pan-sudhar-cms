<?php

namespace App\Http\Controllers\Retailer\Itr;

use App\Http\Controllers\Controller;

class GstReturnController extends Controller
{
    public function index()
    {
        return view('retailer.itr.gst-return');
    }
}