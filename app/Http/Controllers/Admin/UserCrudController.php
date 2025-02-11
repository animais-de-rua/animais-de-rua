<?php

namespace App\Http\Controllers\Admin;

use GemaDigital\Http\Controllers\Admin\UserCrudController as DefaultUserCrudController;

class UserCrudController extends DefaultUserCrudController
{
    #[\Override]
    public function setup(): void
    {
        parent::setup();
    }

    #[\Override]
    public function setupListOperation(): void
    {
        parent::setupListOperation();
    }

    #[\Override]
    public function setupCreateOperation(): void
    {
        parent::setupCreateOperation();
    }

    #[\Override]
    public function setupUpdateOperation(): void
    {
        parent::setupUpdateOperation();
    }
}
