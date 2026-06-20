<?php

namespace App\Jobs;

use App\Models\UserDevice;
use App\Services\LocationService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
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

    public function handle(LocationService $locationService): void
    {
        $device = UserDevice::find($this->deviceId);

        if (!$device) {
            Log::warning("GeoIP Job Skipped: Device not found.", ['device_id' => $this->deviceId]);
            return;
        }

        try {
            $locationService->updateDeviceLocation($device, $this->ipAddress);

            Log::info('Background GeoIP Job completed successfully', ['device_id' => $this->deviceId]);

        } catch (Throwable $e) {
            Log::error('GeoIP Job failed', [
                'device_id' => $this->deviceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}