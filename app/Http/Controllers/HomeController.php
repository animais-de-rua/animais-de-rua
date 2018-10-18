<?php

namespace App\Http\Controllers;

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
}
