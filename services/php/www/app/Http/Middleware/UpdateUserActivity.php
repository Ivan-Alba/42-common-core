<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;

class UpdateUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $updateData = ['last_activity' => now()];

            $updateData['status'] = UserStatus::ONLINE;

            $user->update($updateData);
        }

        return $next($request);
    }
}
