<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EdutechController extends Controller
{
    public function index()
    {
        return view('edutech.index');
    }
}