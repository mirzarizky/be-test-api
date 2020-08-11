<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (! Auth::attempt($request->only(['username', 'password']))) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
                ]);
        }

        $user = Auth::user();
        $device_name = $request->device_name ?? 'web';
        $token_scopes = ['app'];
        $access_token = $user->createToken($device_name, $token_scopes)->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'status_message' => 'Success',
            'payload' => [
                'id_user' => $user->id,
                'username' => $user->username,
                'token' => $access_token
            ]
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
