<?php

namespace App\Jobs;

use App\Models\UserDevice;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Throwable;

class ProcessDeviceLocationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 10;
    public int $backoff = 5;

    protected string $deviceId;
    protected string $ipAddress;

    public function __construct(string $deviceId, string $ipAddress)
    {
        $this->deviceId = $deviceId;
        $this->ipAddress = trim($ipAddress);
    }

    public function handle(): void
    {
        // Localhost pe testing ke liye ek Dummy IP set kar rahe hain
        if (
            app()->environment('local') &&
            (
                $this->ipAddress === '127.0.0.1' ||
                $this->ipAddress === '::1' ||
                str_starts_with($this->ipAddress, '192.168.')
            )
        ) {
            $this->ipAddress = '43.204.105.75'; // AWS India ka ek sample IP
        }

        if (!filter_var($this->ipAddress, FILTER_VALIDATE_IP)) {
            return; // Invalid IP ko ignore karein
        }

        $device = UserDevice::find($this->deviceId);

        if (!$device) {
            return; // Agar device DB mein nahi mila
        }

        try {
            // Free IP-API call (Ye VPN, Proxy aur Location sab batata hai)
            $response = Http::timeout(10)->get("http://ip-api.com/json/{$this->ipAddress}?fields=status,country,city,countryCode,timezone,lat,lon,proxy,hosting");

            if ($response->failed() || $response->json('status') !== 'success') {
                return; // API fail hui toh silently exit karein
            }

            $data = $response->json();

            $country = $data['country'] ?? null;
            $city = $data['city'] ?? null;
            $countryCode = $data['countryCode'] ?? null;
            $timezone = $data['timezone'] ?? null;
            $lat = $data['lat'] ?? null;
            $lon = $data['lon'] ?? null;

            // VPN ya Hosting Server detect karna
            $isProxy = $data['proxy'] ?? false;
            $isHosting = $data['hosting'] ?? false;
            $vpnDetected = ($isProxy || $isHosting) ? 1 : 0;
            $proxyDetected = $isProxy ? 1 : 0;

            // --- RISK ENGINE LOGIC START ---
            $riskScore = $device->risk_score ?? 0;
            $riskReasons = $device->risk_reason ?? [];

            // 1. Agar naya VPN detect hua
            if ($vpnDetected && !$device->vpn_detected) {
                $riskScore += 30;
                $riskReasons[] = 'VPN or Data Center IP Detected';
            }

            // 2. Agar login India ke bahar se ho raha hai (Change 'IN' if needed)
            if ($countryCode && $countryCode !== 'IN') { 
                $riskScore += 50;
                $riskReasons[] = "Suspicious location detected: {$countryCode}";
            }

            // 3. Trust Level set karo based on Score
            $trustLevel = $device->trust_level;
            if ($riskScore >= 70) {
                $trustLevel = 'BLOCKED';
            } elseif ($riskScore >= 40 && $trustLevel !== 'BLOCKED') {
                $trustLevel = 'SUSPICIOUS';
            }
            // --- RISK ENGINE LOGIC END ---

            // Database ko final values se update karo
            $device->update([
                'last_country' => $country,
                'last_city' => $city,
                'country_code' => $countryCode,
                'timezone' => $timezone,
                'lat' => $lat,
                'lon' => $lon,
                'vpn_detected' => $vpnDetected,
                'proxy_detected' => $proxyDetected,
                'risk_score' => $riskScore,
                'risk_reason' => array_unique($riskReasons), // Duplicate reasons hata do
                'trust_level' => $trustLevel
            ]);

            Log::info('Device GeoIP & Risk Flags updated', [
                'device_id' => $this->deviceId,
                'new_score' => $riskScore,
                'trust_level' => $trustLevel
            ]);

        } catch (Throwable $e) {
            Log::error('GeoIP processing failed', [
                'device_id' => $this->deviceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}