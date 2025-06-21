<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use GemaDigital\Http\Controllers\Admin\PageCrudController as DefaultPageCrudController;

class PageCrudController extends DefaultPageCrudController
{
    public function setup(bool $template_name = false): void
    {
        parent::setup($template_name);

        if (! is(['admin', 'translator'], 'website')) {
            CRUD::denyAccess(['list', 'update']);
        }

        CRUD::denyAccess(['create', 'delete']);
    }
}
