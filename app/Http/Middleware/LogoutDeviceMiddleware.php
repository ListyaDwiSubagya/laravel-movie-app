<?php

namespace App\Http\Middleware;

use App\Services\DeviceLimitService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class LogoutDeviceMiddleware
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
        if ($this->isLogoutRequest($request)) {
            $deviceId = Session::get('device_id');

            if ($deviceId) {
                $this->deviceLimitService->logoutDevice($deviceId);
            }
        }

        return $next($request);
    }

    private function isLogoutRequest(Request $request): bool
    {
        return $request->is('logout') || Route::currentRouteName() === 'logout';
    }
}
