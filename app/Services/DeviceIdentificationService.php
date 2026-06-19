<?php

namespace App\Services;

use App\Models\UserDevice;
use Jenssegers\Agent\Agent;
use App\Jobs\ProcessDeviceLocationJob;
use Carbon\Carbon;

class DeviceIdentificationService
{
    const TRUST_NEW = 'NEW';
    const TRUST_VERIFIED = 'VERIFIED';
    const TRUST_TRUSTED = 'TRUSTED';
    const TRUST_SUSPICIOUS = 'SUSPICIOUS';
    const TRUST_BLOCKED = 'BLOCKED';

    public function processDevice($user, string $deviceId, string $appVersion, string $ip, ?string $userAgent, ?string $language, Agent $agent): UserDevice
    {
        $isFallback = str_starts_with($deviceId, 'fb_');
        $userId = ($user && isset($user->id)) ? $user->id : null;

        $context = $this->analyzeDeviceContext($agent, $userAgent);

        // Agar user login nahi hai (Guest)
        if (!$userId) {
            return $this->buildTempDevice($deviceId, $userAgent, $appVersion, $ip, $isFallback, $language, $context);
        }

        // User logged in hai, device dhundo ya naya banao
        $device = UserDevice::firstOrNew([
            'user_id' => $userId,
            'device_id' => $deviceId,
        ]);

        // Agar device already blocked hai, toh aage processing mat karo
        if ($device->exists && $device->trust_level === self::TRUST_BLOCKED) {
            return $device;
        }

        $needsDbUpdate = false;
        $ipChanged = $device->last_ip_address !== $ip;

        // Agar naya device hai ya browser change hua hai, toh characteristics update karo
        if (!$device->exists || $device->user_agent !== $userAgent) {
            $this->updateDeviceCharacteristics($device, $userAgent, $deviceId, $isFallback, $context, $language);
            $needsDbUpdate = true;
        }

        // App version aur language track karo
        if ($device->app_version !== $appVersion) {
            $device->app_version = $appVersion;
            $needsDbUpdate = true;
        }

        if ($language && $device->language !== $language) {
            $device->language = $language;
            $needsDbUpdate = true;
        }

        // Trust Level Upgrade Logic (Agar 7 din purana hai aur 3 baar login kar chuka hai)
        if ($device->exists && $device->trust_level === self::TRUST_VERIFIED && $device->login_count >= 3) {
            $daysOld = Carbon::parse($device->created_at)->diffInDays(now());
            if ($daysOld >= 7) {
                $device->trust_level = self::TRUST_TRUSTED;
                $device->trusted_at = now(); 
                $needsDbUpdate = true;
            }
        }

        // IP Address update
        if ($ipChanged || !$device->exists) {
            $device->last_ip_address = $ip;
            $needsDbUpdate = true;
        }

        // Active status update (Har 5 minute mein ek hi baar DB hit karega)
        if (!$device->last_active_at || $device->last_active_at->diffInMinutes(now()) >= 5) {
            $device->last_active_at = now();
            $needsDbUpdate = true;
        }

        if ($needsDbUpdate) {
            $device->save();
        }

        // Background GeoIP Job Dispatch
        if ($ipChanged && $device->id) {
            ProcessDeviceLocationJob::dispatch($device->id, $ip);
        }

        return $device;
    }

    private function analyzeDeviceContext(Agent $agent, ?string $userAgent): array
    {
        $isBot = $this->isBot($userAgent);
        $isEmulator = $this->isEmulator($userAgent) || $agent->isRobot();

        $deviceType = 'UNKNOWN';
        if ($isBot) $deviceType = 'BOT';
        elseif ($isEmulator) $deviceType = 'EMULATOR';
        elseif ($agent->isMobile()) $deviceType = 'MOBILE';
        elseif ($agent->isTablet()) $deviceType = 'TABLET';
        elseif ($agent->isDesktop()) $deviceType = 'DESKTOP';

        return [
            'browser' => $agent->browser() ?: 'Unknown',
            'platform' => $agent->platform() ?: 'Unknown',
            'device_name' => $agent->device() ?: 'Unknown Device',
            'is_bot' => $isBot,
            'is_emulator' => $isEmulator,
            'device_type' => $deviceType,
        ];
    }

    private function updateDeviceCharacteristics(UserDevice $device, ?string $userAgent, string $deviceId, bool $isFallback, array $context, ?string $language): void
    {
        $fingerprintHash = hash('sha256', implode('|', [
            $deviceId, $context['browser'], $context['platform'], $language, $context['device_type']
        ]));

        $riskScore = $device->risk_score ?? 0;
        $riskReasons = $device->risk_reason ?? [];

        if (!$device->exists) {
            $device->fingerprint_hash = $fingerprintHash;
            $device->device_name = $context['device_name'];
            $device->browser = $context['browser'];
            $device->platform = $context['platform'];
            $device->device_type = $context['device_type'];
            $device->is_emulator = ($context['is_emulator'] || $context['is_bot']);
            
            if ($device->is_emulator) {
                $riskScore += 50;
                $riskReasons[] = 'Emulator or Bot detected on first login';
            }
            
            $device->trust_level = ($isFallback || $context['is_bot'] || $context['is_emulator']) ? self::TRUST_SUSPICIOUS : self::TRUST_NEW;
        } else {
            if ($device->platform !== $context['platform']) {
                $riskScore += 40;
                $riskReasons[] = "Platform anomaly: Expected {$device->platform}, got {$context['platform']}";
            }
            if ($device->browser !== $context['browser']) {
                $riskScore += 20;
                $riskReasons[] = "Browser anomaly: Expected {$device->browser}, got {$context['browser']}";
            }

            $device->fingerprint_hash = $fingerprintHash;
            $device->device_name = $context['device_name'];
            $device->browser = $context['browser'];
            $device->platform = $context['platform'];
            $device->device_type = $context['device_type'];

            if ($riskScore >= 40 && !in_array($device->trust_level, [self::TRUST_BLOCKED, self::TRUST_SUSPICIOUS])) {
                $device->trust_level = self::TRUST_SUSPICIOUS;
            }
        }
        
        $device->user_agent = $userAgent;
        $device->risk_score = $riskScore;
        $device->risk_reason = array_unique($riskReasons);
    }

    private function buildTempDevice(string $deviceId, ?string $userAgent, string $appVersion, string $ip, bool $isFallback, ?string $language, array $context): UserDevice
    {
        $device = new UserDevice();
        // ... Set temp properties for unauthenticated users ...
        $device->device_id = $deviceId;
        $device->last_ip_address = $ip;
        $device->trust_level = self::TRUST_NEW;
        return $device;
    }

    private function isBot(?string $userAgent): bool
    {
        if (!$userAgent) return false;
        $botPatterns = ['PostmanRuntime', 'curl', 'python-requests', 'GuzzleHttp', 'HeadlessChrome'];
        foreach ($botPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $userAgent)) return true;
        }
        return false;
    }

    private function isEmulator(?string $userAgent): bool
    {
        if (!$userAgent) return false;
        $emulatorPatterns = ['Android.*Build', 'Genymotion', 'Nox', 'BlueStacks', 'LDPlayer'];
        foreach ($emulatorPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $userAgent)) return true;
        }
        return false;
    }
}