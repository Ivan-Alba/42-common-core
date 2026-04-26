<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Enums\UserStatus;
use Carbon\Carbon;

class ClearInactiveUsers extends Command
{
    // The name and signature of the console command
    protected $signature = 'users:clear-inactive';

    // The console command description
    protected $description = 'Update status to AWAY or OFFLINE based on last_activity';

    public function handle()
    {
        $now = Carbon::now();

        /* 1. Set to AWAY users who haven't been active for 5 minutes */
        $awayUsers = User::whereIn('status', [UserStatus::ONLINE, UserStatus::PLAYING])
            ->where('last_activity', '<', $now->copy()->subMinutes(5))
            ->get();
		
		foreach ($awayUsers as $user) {
			$user->update(['status' => UserStatus::AWAY]);
			$this->info("User {$user->email} set to AWAY due to inactivity.");
		}

        /* 2. Set to OFFLINE users who haven't been active for 30 minutes 
           (Matching your React session timeout) */
        $inactiveUsers = User::where('status', UserStatus::AWAY)
            ->where('last_activity', '<', $now->copy()->subMinutes(30))
            ->get();

        foreach ($inactiveUsers as $user) {
            /* Revoke all Sanctum tokens (same as your manual logout) */
            $user->tokens()->delete();

            /* Set status to OFFLINE */
            $user->update(['status' => UserStatus::OFFLINE]);

            $this->info("User {$user->email} forced OFFLINE due to inactivity.");
        }

        $this->info('User statuses and tokens updated successfully.');
    }
}
