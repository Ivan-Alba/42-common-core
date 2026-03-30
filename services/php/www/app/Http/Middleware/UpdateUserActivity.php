<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MatchmakingController;

class UpdateUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $updateData = ['last_activity' => now()];

            $updateData['status'] = UserStatus::ONLINE;

            $safeRoutes = [
                'v1/matchmaking/*',
                'v1/users/*/friends',
            ];

            $isSafe = false;
            foreach ($safeRoutes as $route) {
                if ($request->is($route)) {
                    $isSafe = true;
                    break;
                }
            }

            if (!$isSafe) {
                app(MatchmakingController::class)->handleUserDisconnection($user);
            }

            $user->update($updateData);
        }

        return $next($request);
    }
}
