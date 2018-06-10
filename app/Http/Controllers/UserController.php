<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
	public function terminal()
    {
        $this->data['title'] = trans('Terminal');
        $this->data['user'] = \Auth::user();

        return view('auth.terminal', $this->data);
    }

	public function terminal_run(Request $request)
    {
    	if(\Auth::user()->is_superadmin()) {
			echo shell_exec($request->input('cmd'));
        }
    }
}
