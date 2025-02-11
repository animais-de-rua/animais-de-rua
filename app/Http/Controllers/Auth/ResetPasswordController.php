<?php

namespace App\Http\Controllers\Auth;

use Backpack\CRUD\app\Http\Controllers\Auth\ResetPasswordController as OriginalResetPasswordController;

class ResetPasswordController extends OriginalResetPasswordController
{
    protected ?string $redirectTo = '/admin/dashboard';
}
