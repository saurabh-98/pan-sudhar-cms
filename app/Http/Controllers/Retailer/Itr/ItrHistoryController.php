<?php

namespace App\Http\Controllers\Retailer\Itr;

use App\Http\Controllers\Controller;

class ItrHistoryController extends Controller
{
    public function index()
    {
        return view('retailer.itr.history');
    }
}