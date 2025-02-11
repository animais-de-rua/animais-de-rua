<?php

namespace App\Http\Controllers\API;

use GemaDigital\Http\Controllers\API\APIController as DefaultAPIController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends DefaultAPIController
{
    /**
     * Get User.
     */
    public function getUser(Request $request): Response
    {
        return json_response([
            'user' => $request->user(),
        ]);
    }
}
