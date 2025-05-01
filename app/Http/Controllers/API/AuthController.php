<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\LoginRequest;
use App\Models\User;
use GemaDigital\Http\Controllers\API\APIController as DefaultAPIController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends DefaultAPIController
{
    /**
     * Login.
     */
    public function login(LoginRequest $request): Response
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return json_response([
            'user' => $user,
            'token' => $user->createToken($request->device_name ?? Config::get('app.name'))->plainTextToken,
        ]);
    }

    /**
     * User register.
     */
    public function register(Request $request): Response
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return json_response([
            'user' => $user,
            'token' => $user->createToken($request->device_name ?? Config::get('app.name'))->plainTextToken,
        ]);
    }

    /**
     * Logout.
     */
    public function logout(Request $request): Response
    {
        user()->tokens()->delete();

        return json_response(true);
    }
}
