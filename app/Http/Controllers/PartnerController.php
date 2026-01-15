<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = [
            ['name' => 'Partner 1', 'logo' => 'https://via.placeholder.com/200'],
            ['name' => 'Partner 2', 'logo' => 'https://via.placeholder.com/200'],
            ['name' => 'Partner 3', 'logo' => 'https://via.placeholder.com/200'],
            ['name' => 'Partner 4', 'logo' => 'https://via.placeholder.com/200'],
        ];

        return view('partner.index', compact('partners'));
    }
}
