<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function register(Request $request) {

        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);


        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);

        if (!$user) {
            Log::error('User failed to save.');
            return response()->json(['message' => 'Gagal simpan user'], 500);
        }

        Auth::login($user);

        Log::info('User registered and logged in', ['id' => $user->id]);

        return response()->json(['message' => 'Registered successfully']);
    }
    public function login(Request $request) {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json(['message' => 'Login success']);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'Logged out']);
    }
}

