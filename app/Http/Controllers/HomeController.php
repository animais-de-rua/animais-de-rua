<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application home.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('welcome');
    }

    public function import()
    {
        return view('import');
    }

    public function termsOfService()
    {
        return view('legal.terms-of-service');
    }

    public function privacyPolicy()
    {
        return view('legal.privacy-policy');
    }
}
