<?php

namespace App\Http\Controllers\Admin;

use Session;

class ViewAsController extends \App\Http\Controllers\Controller
{
    public function view_as_role($role)
    {
        if (!admin()) {
            abort(403);
        }

        if ($role != 'admin') {
            Session::put('role', $role);
        } else {
            $this->clearAll();
            $this->clearAll();
        }

        return redirect(url()->previous());
    }

    public function view_as_permission($permission, $state)
    {
        if (!admin()) {
            abort(403);
        }

        if ($permission != 'all') {
            $permissions = Session::get('permissions', []);

            if ($state) {
                array_push($permissions, $permission);
            } else {
                unset($permissions[array_search($permission, $permissions)]);
            }

            if (sizeof($permissions)) {
                Session::put('permissions', $permissions);
            } else {
                Session::remove('permissions');
            }
        } else {
            $this->clearAll();
        }

        return redirect(url()->previous());
    }

    public function view_as_headquarter($headquarter, $state)
    {
        if (!admin()) {
            abort(403);
        }

        if ($headquarter != 'all') {
            $headquarters = Session::get('headquarters', []);

            if ($state) {
                array_push($headquarters, $headquarter);
            } else {
                unset($headquarters[array_search($headquarter, $headquarters)]);
            }

            if (sizeof($headquarters)) {
                Session::put('headquarters', $headquarters);
            } else {
                Session::remove('headquarters');
            }
        } else {
            $this->clearAll();
        }

        return redirect(url()->previous());
    }

    public function view_as_clear()
    {
        $this->clearAll();

        return redirect(url()->previous());
    }

    public function clearAll()
    {
        Session::remove('role');
        Session::remove('permissions');
        Session::remove('headquarters');
    }
}
