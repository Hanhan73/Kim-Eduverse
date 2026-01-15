<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function profile()
    {
        return view('about.profile');
    }

    public function organization()
    {
        return view('about.organization');
    }
}