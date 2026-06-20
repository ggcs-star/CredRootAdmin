<?php

namespace App\Services;

use App\Models\UserDevice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationService
{

    public function updateDeviceLocation(UserDevice $device, string $ip): void
    {
        if (app()->environment('local') && in_array($ip, ['127.0.0.1', '::1'])) {
            $ip = '43.204.105.75';
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return;
        }

        try {
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}?fields=status,country,city,countryCode,timezone,lat,lon,proxy,hosting");

            if ($response->successful() && $response->json('status') === 'success') {
                $data = $response->json();

                $countryCode = $data['countryCode'] ?? null;
                $isProxy = $data['proxy'] ?? false;
                $isHosting = $data['hosting'] ?? false;

                $vpnDetected = ($isProxy || $isHosting) ? 1 : 0;
                $proxyDetected = $isProxy ? 1 : 0;

                $riskScore = $device->risk_score ?? 0;
                $riskReasons = $device->risk_reason ?? [];

                if ($vpnDetected && !$device->vpn_detected) {
                    $riskScore += 30;
                    $riskReasons[] = 'VPN or Data Center IP Detected';
                }

                if ($countryCode && $countryCode !== 'IN') {
                    $riskScore += 50;
                    $riskReasons[] = "Suspicious location detected: {$countryCode}";
                }

                $trustLevel = $device->trust_level;
                if ($riskScore >= 70) {
                    $trustLevel = 'BLOCKED';
                } elseif ($riskScore >= 40 && $trustLevel !== 'BLOCKED') {
                    $trustLevel = 'SUSPICIOUS';
                }

                $device->update([
                    'last_country' => $data['country'] ?? null,
                    'last_city' => $data['city'] ?? null,
                    'country_code' => $countryCode,
                    'timezone' => $data['timezone'] ?? null,
                    'lat' => $data['lat'] ?? null,
                    'lon' => $data['lon'] ?? null,
                    'vpn_detected' => $vpnDetected,
                    'proxy_detected' => $proxyDetected,
                    'risk_score' => $riskScore,
                    'risk_reason' => array_unique($riskReasons),
                    'trust_level' => $trustLevel
                ]);

            }
        } catch (\Exception $e) {
            Log::error("GeoIP Fetch Failed for IP: {$ip}", ['error' => $e->getMessage()]);
        }
    }
}