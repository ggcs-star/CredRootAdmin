<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'mobile' => 'required|digits:10',
        ]);

        $response = $this->authService->initiateRegistration($validated);

        return response()->json($response, $response['code'] ?? 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $response = $this->authService->processOtpVerification($request->email, $request->otp, $request);

        return response()->json($response, $response['code'] ?? 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $response = $this->authService->processLogin($credentials, $request);

        return response()->json($response, $response['code'] ?? 200);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        $response = $this->authService->processTokenRefresh($request->refresh_token, $request);

        return response()->json($response, $response['code'] ?? 200);
    }

    public function activeSessions()
    {
        $response = $this->authService->getActiveSessions(auth('api')->user());
        return response()->json($response, $response['code'] ?? 200);
    }


    public function logout(Request $request)
    {
        $request->validate([
            'session_id' => 'nullable|string',
            'refresh_token' => 'nullable|string',
            'logout_all' => 'nullable|boolean'
        ]);

        $response = $this->authService->processUnifiedLogout(auth('api')->user(), $request);

        return response()->json($response, $response['code'] ?? 200);
    }


}