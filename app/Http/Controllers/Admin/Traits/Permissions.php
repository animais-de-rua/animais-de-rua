<?php

namespace App\Http\Controllers\Admin\Traits;

trait Permissions
{
    public function restrictTo($roles, $permissions = null)
    {
        if (! restrictTo($roles, $permissions)) {
            abort(403);
        }
    }

    public function removeDefaultActions()
    {
        if (! backpack_user()->hasRole('admin')) {
            $this->crud->removeButton('update');
            $this->crud->removeButton('delete');

            $this->crud->allowAccess(['list', 'create']);
            $this->crud->denyAccess(['update', 'reorder', 'delete']);
        }
    }
}
