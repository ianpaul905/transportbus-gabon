<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function mentions()
    {
        return view('legal.mentions');
    }

    public function cgv()
    {
        return view('legal.cgv');
    }

    public function confidentialite()
    {
        return view('legal.confidentialite');
    }
}
