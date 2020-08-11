<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Incorrect email or password.'],
                ]);
        }

        $device_name = $request->device_name ?? 'web';
        $token_scopes = ['app'];
        $access_token = $user->createToken($device_name, $token_scopes)->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $access_token
        ]);
    }

    public function logout(Request $request)
    {
        $current_access_token = $request->user()->currentAccessToken();
        $request->user()->tokens()->where('id', $current_access_token->id)->delete();

        return response()->json([
            'message' => 'successfully logout'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }
}
