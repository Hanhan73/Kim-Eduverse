<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EdutechController extends Controller
{
    public function index()
    {

        if (auth()->user()->role == 'admin') {
            return view('edutech.index');


            return view('edutech.maintenance');
        }
    }
}
