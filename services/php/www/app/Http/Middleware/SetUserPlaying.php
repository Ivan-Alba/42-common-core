<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;

class SetUserPlaying
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $updateData = [
                'last_activity' => now(),
            ];

            if ($user->status !== UserStatus::PLAYING) {
                $updateData['status'] = UserStatus::PLAYING;
            }

            $user->update($updateData);
        }

        return $next($request);
    }
}
