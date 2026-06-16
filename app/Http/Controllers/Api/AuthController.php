<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register User
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully'
        ], 201);
    }

    /**
     * Login User
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Current Logged In User
     */
    public function me()
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'user' => $user,
            'roles' => $user->getRoleNames(),
        ]);
    }

    /**
     * Refresh JWT Token
     */
    public function refresh()
    {
        return $this->respondWithToken(
            auth('api')->refresh()
        );
    }

    /**
     * Logout User
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * JWT Token Response
     */
    protected function respondWithToken($token)
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'roles' => $user->getRoleNames(),
        ]);
    }
}
