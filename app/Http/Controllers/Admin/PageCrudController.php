<?php

namespace App\Http\Controllers\Admin;

class PageCrudController extends \Backpack\PageManager\app\Http\Controllers\Admin\PageCrudController
{
    public function setup($template_name = false)
    {
        parent::setup($template_name);

        $this->crud->removeButton('create');
        $this->crud->removeButton('delete');
    }
}
