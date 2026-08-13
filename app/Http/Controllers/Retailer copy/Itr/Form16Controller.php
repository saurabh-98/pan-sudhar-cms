<?php

namespace App\Http\Controllers\Retailer\Itr;

use App\Http\Controllers\Controller;

class Form16Controller extends Controller
{
    public function index()
    {
        return view('retailer.itr.form16');
    }
}