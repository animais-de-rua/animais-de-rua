<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;

class PageCrudController extends \Backpack\PageManager\app\Http\Controllers\Admin\PageCrudController
{
    use Permissions;

    public function setup($template_name = false)
    {
        parent::setup($template_name);

        $this->crud->removeButton('create');
        $this->crud->removeButton('delete');
    }
}
