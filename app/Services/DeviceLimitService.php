<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;

class DeviceLimitService
{
    public function registerDevice(User $user) 
    {
        $deviceInfo = $this->getDeviceInfo();

        $existingDevice = $this->findExistingDevice($user, $deviceInfo);
        
        if ($existingDevice) {
            // Update last_active jika device ditemukan
            $existingDevice->update([
                'last_active' => now(),
            ]);
            session(['device_id' => $existingDevice->device_id]);
            return $existingDevice;
        }

        if ($this->hasReachedDeviceLimit($user)) {
            return false;
        }

        $device = $this->createNewDevice($user, $deviceInfo);
        session(['device_id' => $device->device_id]);
        return $device;
    }

    private function getDeviceInfo(){
        return [
            'device_name' => $this->generateDeviceName(),
            'device_type' => Agent::isDesktop() ? 'desktop' : (Agent::isPhone() ? 'phone' : 'tablet'),
            'platform' => Agent::platform(),
            'platform_version' => Agent::version(Agent::platform()),
            'browser' => Agent::browser(),
            'browser_version' => Agent::version(Agent::browser()),
        ];
    }

    private function generateDeviceName()
    {
        return ucfirst(Agent::platform()). ' ' . ucfirst(Agent::browser());
    }

    private function findExistingDevice(User $user, array $deviceInfo)
    {
        return UserDevice::where('user_id', $user->id)
            ->where('device_type', $deviceInfo['device_type'])
            ->where('platform', $deviceInfo['platform'])
            ->where('browser', $deviceInfo['browser'])
            ->first();
    }

    private function createNewDevice(User $user, array $deviceInfo)
    {
        return UserDevice::create([
            'user_id' => $user->id,
            'device_name' => $deviceInfo['device_name'],
            'device_id' => $this->generateDeviceId(),
            'device_type' => $deviceInfo['device_type'],
            'platform' => $deviceInfo['platform'],
            'platform_version' => $deviceInfo['platform_version'],
            'browser' => $deviceInfo['browser'],
            'browser_version' => $deviceInfo['browser_version'],
            'last_active' => now(),
        ]);
    }
    private function generateDeviceId()
    {
        return Str::random(32);
    }

    private function hasReachedDeviceLimit(User $user)
    {
        $maxDevices = $user->getCurrentPlan()->max_device ?? 1;
        return UserDevice::where('user_id', $user->id)->count() >= $maxDevices;
    }

    public function logoutDevice($deviceId)
    {
        UserDevice::where('device_id', $deviceId)->delete();
        session()->forget('device_id');
    }
}
