<?php

namespace App\Http\Controllers\Admin\Traits;

use Auth;

trait Permissions
{
    public function restrictTo($roles, $permissions)
    {
        if (!restrictTo($roles, $permissions)) {
            abort(403);
        }
    }

    public function removeDefaultActions()
    {
        if (!Auth::user()->hasRole('admin')) {
            $this->crud->removeButton('update');
            $this->crud->removeButton('delete');

            $this->crud->allowAccess(['list', 'create']);
            $this->crud->denyAccess(['update', 'reorder', 'delete']);
        }
    }
}
