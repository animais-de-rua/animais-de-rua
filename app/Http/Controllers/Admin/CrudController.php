<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HandleDropzoneUploadHelper;

class CrudController extends \Backpack\CRUD\app\Http\Controllers\CrudController
{
	use HandleDropzoneUploadHelper;

    public function json($data)
    {
        return response($data, 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Content-Type', 'application/json');
    }
}
