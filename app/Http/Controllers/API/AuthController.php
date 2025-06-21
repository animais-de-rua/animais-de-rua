<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\LoginRequest;
use App\Models\User;
use GemaDigital\Http\Controllers\API\APIController as DefaultAPIController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends DefaultAPIController
{
    /**
     * Login.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken($request->device_name ?? config('app.name'))->plainTextToken,
        ]);
    }

    /**
     * User register.
     */
    public function register(Request $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken($request->device_name ?? config('app.name'))->plainTextToken,
        ]);
    }

    /**
     * Logout.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json(true);
    }
}
