<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	/**
     * Login - Create Token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $validated['email'])->first();

        // Check password
        if(!$user || !Hash::check($validated['password'], $user->password)) {
            return response(['message' => 'Bad creds'], 401);
        }

        $token = $user->createToken('ApiToken')->plainTextToken;

        $response = [
            'token' => $token
        ];

        return response($response, 201);
    }
	
	/**
     * Logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return response(['message' => 'Logged out'], 201);
    }
}