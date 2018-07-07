<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HandleDropzoneUploadHelper;

class CrudController extends \Backpack\CRUD\app\Http\Controllers\CrudController
{
	use HandleDropzoneUploadHelper;

}
