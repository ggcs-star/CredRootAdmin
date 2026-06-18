<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $otp = rand(100000, 999999);

        Cache::put(
            'register_' . $request->email,
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'otp' => $otp,
            ],
            now()->addMinutes(10)
        );

        Mail::to($request->email)->send(
            new OtpMail($otp)
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully'
        ]);

    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);


        $data = Cache::get('register_' . $request->email);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired'
            ], 400);
        }

        if ($data['otp'] != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $user->assignRole('user');

        Cache::forget('register_' . $request->email);

        return response()->json([
            'success' => true,
            'message' => 'Registration completed successfully'
        ]);


    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = auth('api')->user();

        $onboardingData = null;

        if ($user->current_step < 6) {
            $profile = \App\Models\UserProfile::where('user_id', $user->id)->first();
            $company = \App\Models\Company::with('members')->where('user_id', $user->id)->first();
            $bankAccounts = $company ? \App\Models\CompanyBankAccount::where('company_id', $company->id)->get() : [];
$activeLead = \App\Models\Lead::where('user_id', $user->id)
                            ->latest()
                            ->first();
            $onboardingData = [
                'profile' => $profile,
                'company' => $company,
                'bank_accounts' => $bankAccounts,
                'active_lead' => $activeLead,
            ];
        }

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'current_step' => $user->current_step,
            ],
            'onboarding_data' => $onboardingData
        ]);
    }


    public function me()
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'user' => $user,
            'roles' => $user->getRoleNames(),
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(
            auth('api')->refresh()
        );
    }


    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }


    protected function respondWithToken($token)
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'roles' => $user->getRoleNames(),
        ]);
    }
}
