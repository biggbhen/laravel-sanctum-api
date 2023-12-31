<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

class AuthController extends Controller
{
    //register user
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users, email',
            'password' => 'required|string|confirmed'
        ]);
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('access_token')->plainTextToken;
        $response = ['user' => $user, 'token' => $token];

        return response($response, 201);
    }

    //register user
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // check user email
        $user = User::where('email', $fields['email'])->first();

        // check user password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('access_token')->plainTextToken;
        $response = ['user' => $user, 'token' => $token];

        return response($response, 201);
    }

    // logout user
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'logged out'
        ];
    }
}
