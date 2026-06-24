<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use App\Models\DeviceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\OtpMail;
use Jenssegers\Agent\Agent;

class AuthService
{
    protected $deviceService;
    protected $riskService;
    protected $locationService;

    public function __construct(
        DeviceIdentificationService $deviceService,
        SecurityRiskService $riskService,
        LocationService $locationService
    ) {
        $this->deviceService = $deviceService;
        $this->riskService = $riskService;
        $this->locationService = $locationService;
    }

    public function initiateRegistration(array $data): array
    {
        $otp = rand(100000, 999999);

        Cache::put(
            'register_' . $data['email'],
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'mobile' => $data['mobile'],
                'otp' => $otp,
            ],
            now()->addMinutes(10)
        );

        Mail::to($data['email'])->send(new OtpMail($otp));

        return ['success' => true, 'code' => 200, 'message' => 'OTP sent successfully'];
    }

    public function processOtpVerification(string $email, string $otp, Request $request): array
    {
        $registerData = Cache::get('register_' . $email);
        $loginData = Cache::get('login_otp_' . $email);

        if (!$registerData && !$loginData) {
            return ['success' => false, 'code' => 400, 'message' => 'OTP expired or invalid request.'];
        }

        $deviceData = $this->extractDeviceData($request);

        if ($registerData) {
            if ($registerData['otp'] != $otp) {
                return ['success' => false, 'code' => 400, 'message' => 'Invalid OTP'];
            }

            try {
                DB::beginTransaction();

                $user = User::create([
                    'name' => $registerData['name'],
                    'email' => $registerData['email'],
                    'password' => $registerData['password'],
                    'mobile' => $registerData['mobile'],
                ]);
                $user->assignRole('user');
                Cache::forget('register_' . $email);

                $device = $this->processAndSecureDevice($user, $deviceData, $request->ip());

                $refreshToken = Str::random(64);
                DeviceSession::create([
                    'user_id' => $user->id,
                    'user_device_id' => $device->id,
                    'refresh_token' => hash('sha256', $refreshToken),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'expires_at' => now()->addDays(30),
                    'last_used_at' => now(),
                ]);

                $token = auth('api')->login($user);
                DB::commit();

                return $this->buildLoginResponse($user, $device, $token, $refreshToken, 'Registration successful!', 200);

            } catch (\Exception $e) {
                DB::rollBack();
                return ['success' => false, 'code' => 500, 'message' => 'Server Error: ' . $e->getMessage()];
            }
        }


        if ($loginData) {
            if ($loginData['otp'] != $otp) {
                $device = UserDevice::where('device_id', $deviceData['deviceId'])->first();
                if ($device)
                    $device->increment('failed_attempts');

                return ['success' => false, 'code' => 400, 'message' => 'Invalid OTP'];
            }

            try {
                DB::beginTransaction();

                $user = User::where('email', $email)->first();
                $device = $this->processAndSecureDevice($user, $deviceData, $request->ip());
                Cache::forget('login_otp_' . $email);

                DeviceSession::where('user_device_id', $device->id)->whereNull('revoked_at')->update([
                    'revoked_at' => now(),
                    'revoke_reason' => 'New 2FA Login Override'
                ]);

                $refreshToken = Str::random(64);
                DeviceSession::create([
                    'user_id' => $user->id,
                    'user_device_id' => $device->id,
                    'refresh_token' => hash('sha256', $refreshToken),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'expires_at' => now()->addDays(30),
                    'last_used_at' => now(),
                ]);

                $token = auth('api')->login($user);
                DB::commit();

                return $this->buildLoginResponse($user, $device, $token, $refreshToken, 'Device verified! Login successful.', 200);

            } catch (\Exception $e) {
                DB::rollBack();
                return ['success' => false, 'code' => 500, 'message' => 'Server Error: ' . $e->getMessage()];
            }
        }
    }

    public function processLogin(array $credentials, Request $request): array
    {
        if (!$token = auth('api')->attempt($credentials)) {
            return ['success' => false, 'code' => 401, 'message' => 'Invalid credentials'];
        }

        $user = auth('api')->user();
        $ip = $request->ip();

        $deviceData = $this->extractDeviceData($request);

        $device = $this->deviceService->processDevice(
            $user,
            $deviceData['deviceId'],
            $deviceData['appVersion'],
            $ip,
            $deviceData['userAgent'],
            $deviceData['language'],
            $deviceData['agent']
        );
        $this->locationService->updateDeviceLocation($device, $ip);

        $riskAnalysis = $this->riskService->analyzeRisk($device, $user, $ip);

        if ($riskAnalysis['action'] === 'BLOCK' || $device->trust_level === 'BLOCKED') {
            $device->update(['trust_level' => 'BLOCKED', 'blocked_at' => now()]);
            auth('api')->logout();

            return [
                'success' => false,
                'code' => 403,
                'message' => 'Access blocked due to high security risk.',
                'risk_reasons' => $riskAnalysis['reasons'] ?? ['Device flagged as BLOCKED.']
            ];
        }

        if ($riskAnalysis['action'] === 'REQUIRE_OTP') {
            auth('api')->logout();

            $loginOtp = rand(100000, 999999);
            Cache::put('login_otp_' . $user->email, [
                'email' => $user->email,
                'device_id' => $device->id,
                'otp' => $loginOtp,
            ], now()->addMinutes(10));

            Mail::to($user->email)->send(new OtpMail($loginOtp));

            return [
                'success' => false,
                'code' => 401,
                'requires_otp' => true,
                'message' => 'Suspicious login detected. OTP sent to email for verification.',
                'device_id' => $device->id,
                'risk_score' => $riskAnalysis['risk_score']
            ];
        }

        try {
            DB::beginTransaction();

            DeviceSession::where('user_device_id', $device->id)->whereNull('revoked_at')->update([
                'revoked_at' => now(),
                'revoke_reason' => 'New Login'
            ]);

            $refreshToken = Str::random(64);
            DeviceSession::create([
                'user_id' => $user->id,
                'user_device_id' => $device->id,
                'refresh_token' => hash('sha256', $refreshToken),
                'ip_address' => $ip,
                'user_agent' => $deviceData['userAgent'],
                'expires_at' => now()->addDays(30),
                'last_used_at' => now(),
            ]);

            DB::commit();

            return $this->buildLoginResponse($user, $device, $token, $refreshToken, 'Login successful.', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'code' => 500, 'message' => 'Session Creation Failed.'];
        }
    }


    public function processTokenRefresh(string $rawRefreshToken, Request $request): array
    {
        $hashedToken = hash('sha256', $rawRefreshToken);

        $session = DeviceSession::where('refresh_token', $hashedToken)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$session) {
            return ['success' => false, 'code' => 401, 'message' => 'Invalid or expired refresh token. Please login again.'];
        }

        $user = User::find($session->user_id);
        $device = $session->device;

        try {
            DB::beginTransaction();

            $session->update([
                'revoked_at' => now(),
                'revoke_reason' => 'Token Rotated'
            ]);

            $newRefreshToken = Str::random(64);
            DeviceSession::create([
                'user_id' => $user->id,
                'user_device_id' => $device->id,
                'refresh_token' => hash('sha256', $newRefreshToken),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'expires_at' => now()->addDays(30),
                'last_used_at' => now(),
            ]);

            $newAccessToken = auth('api')->login($user);

            DB::commit();

            return $this->buildLoginResponse($user, $device, $newAccessToken, $newRefreshToken, 'Token rotated successfully.', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'code' => 500, 'message' => 'Token rotation failed.'];
        }
    }


    private function extractDeviceData(Request $request): array
    {
        $userAgent = $request->userAgent();
        $language = $request->header('Accept-Language');
        $ip = $request->ip();

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $rawDeviceId = $request->header('X-Device-ID');
        $deviceId = $rawDeviceId ?: 'fb_' . hash('sha256', $userAgent . $language);

        return [
            'userAgent' => $userAgent,
            'language' => $language,
            'appVersion' => $request->header('X-App-Version', '1.0.0'),
            'agent' => $agent,
            'deviceId' => $deviceId
        ];
    }

    private function processAndSecureDevice($user, $deviceData, $ip)
    {
        $device = $this->deviceService->processDevice(
            $user,
            $deviceData['deviceId'],
            $deviceData['appVersion'],
            $ip,
            $deviceData['userAgent'],
            $deviceData['language'],
            $deviceData['agent']
        );
        $this->locationService->updateDeviceLocation($device, $ip);
        $device->update(['trust_level' => 'VERIFIED', 'failed_attempts' => 0, 'last_login_at' => now()]);

        return $device;
    }

    private function buildLoginResponse($user, $device, $token, $refreshToken, $message, $code): array
    {
        return [
            'success' => true,
            'code' => $code,
            'message' => $message,
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'current_step' => $user->current_step,
            ],
            'device_info' => [
                'id' => $device->id,
                'trust_level' => $device->trust_level,
            ]
        ];
    }
    public function getActiveSessions(User $user): array
    {
        $sessions = DeviceSession::with([
            'device' => function ($query) {
                $query->select(
                    'id',
                    'device_name',
                    'device_type',
                    'platform',
                    'browser',
                    'last_city',
                    'last_country',
                    'trust_level'
                );
            }
        ])
            ->where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->orderBy('last_used_at', 'desc')
            ->get();

        $sessions->makeHidden(['refresh_token']);

        return ['success' => true, 'code' => 200, 'data' => $sessions];
    }

    public function processUnifiedLogout(User $user, Request $request): array
    {
        $sessionId = $request->input('session_id');
        $rawRefreshToken = $request->input('refresh_token');
        $logoutAll = $request->boolean('logout_all');

        if ($logoutAll) {
            DeviceSession::where('user_id', $user->id)
                ->whereNull('revoked_at')
                ->update([
                    'revoked_at' => now(),
                    'revoke_reason' => 'Global Logout'
                ]);

            auth('api')->logout();

            return ['success' => true, 'code' => 200, 'message' => 'Logged out from all devices successfully.'];
        }

        if ($sessionId) {
            $session = DeviceSession::where('id', $sessionId)
                ->where('user_id', $user->id)
                ->first();

            if (!$session) {
                return ['success' => false, 'code' => 404, 'message' => 'Session not found or already logged out.'];
            }

            $session->update([
                'revoked_at' => now(),
                'revoke_reason' => 'User Remote Logout'
            ]);

            return ['success' => true, 'code' => 200, 'message' => 'Successfully logged out from that device.'];
        }

        if ($rawRefreshToken) {
            $hashedToken = hash('sha256', $rawRefreshToken);
            DeviceSession::where('user_id', $user->id)
                ->where('refresh_token', $hashedToken)
                ->whereNull('revoked_at')
                ->update([
                    'revoked_at' => now(),
                    'revoke_reason' => 'User Logout'
                ]);
        }

        auth('api')->logout();

        return ['success' => true, 'code' => 200, 'message' => 'Logged out successfully.'];
    }
    public function resendOtp(string $email): array
    {
        $registerData = Cache::get('register_' . $email);
        $loginData = Cache::get('login_otp_' . $email);

        if (!$registerData && !$loginData) {
            return [
                'success' => false, 
                'code' => 400, 
                'message' => 'Session expired. Please restart the process (Login or Register again).'
            ];
        }

        $newOtp = rand(100000, 999999);

        if ($registerData) {
            $registerData['otp'] = $newOtp;
            Cache::put('register_' . $email, $registerData, now()->addMinutes(10));
        } elseif ($loginData) {
            $loginData['otp'] = $newOtp;
            Cache::put('login_otp_' . $email, $loginData, now()->addMinutes(10));
        }

        Mail::to($email)->send(new OtpMail($newOtp));

        return [
            'success' => true, 
            'code' => 200, 
            'message' => 'A new OTP has been sent to your email.'
        ];
    }
}