<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class SubscribeController extends Controller implements HasMiddleware
{
     public static function middleware(): array
    {
        return [
            'auth',
        ];
    }
    public function showPlans()
    {
        $plans = Plan::all();
        return view('subscribe.plans', compact('plans'));
    }

    public function checkoutPlan(Plan $plan)
    {
        $user = Auth::user();
        return view('subscribe.checkout', compact('plan', 'user'));
    }

    public function processCheckout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $plan = Plan::findOrFail($request->plan_id);

        // Sekarang Intelephense tahu bahwa $user punya relasi memberships()
        $user->memberships()->create([
            'plan_id' => $request->plan_id,
            'active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays($plan->duration),
        ]);

        return redirect()->route('subscribe.success');
    }

    public function showSuccess()
    {
        return view('subscribe.success');
    }
}

