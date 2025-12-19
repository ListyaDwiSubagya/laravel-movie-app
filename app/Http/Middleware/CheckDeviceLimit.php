<?php

namespace App\Http\Middleware;

use App\Models\UserDevice;
use App\Services\DeviceLimitService;
use App\Services\DeviceService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDeviceLimit
{
    protected $deviceLimitService;

    public function __construct(DeviceLimitService $deviceLimitService)
    {
        $this->deviceLimitService = $deviceLimitService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        if ($request->routeIs('login') || $request->routeIs('logout')) {
            return $next($request);
        }

        $sessionDeviceId = session('device_id');

        $device = UserDevice::where('user_id', $user->id)
            ->where('device_id', $sessionDeviceId)
            ->first();

        if (!$device) {
            // Jika tidak ada, coba daftarkan ulang
            $device = $this->deviceLimitService->registerDevice($user);

            if (!$device) {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['device' => 'Anda telah mencapai batas maksimum perangkat. Silakan logout dari perangkat lain.']);
            }
        }

        return $next($request);
    }
}
