<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use GemaDigital\Http\Controllers\API\APIController as DefaultAPIController;
use GemaDigital\Http\Requests\API\GateCheckRequest;
use Illuminate\Http\Response;

class UserController extends DefaultAPIController
{
    /**
     * Read a User
     */
    public function read(GateCheckRequest $request, User $user): Response
    {
        return response()->api([
            'user' => $user,
        ]);
    }
}
