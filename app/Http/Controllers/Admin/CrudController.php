<?php

namespace App\Http\Controllers\Admin;

class CrudController extends \Backpack\CRUD\app\Http\Controllers\CrudController
{
    public function json($data)
    {
        return response($data, 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Content-Type', 'application/json');
    }
}
