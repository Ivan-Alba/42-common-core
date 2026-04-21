<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AchievementService;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */

    public function created(User $user): void
    {

        $user->stats()->create([
            // All these are handled by the database defaults:
            // 'level'         => 1,
            // 'experience'    => 0,
            // 'ranked_points' => 0,
            // 'last_rank_pos' => null,
            // 'wins'          => 0,
            // 'losses'        => 0,
            // 'draws'         => 0,
            // 'campaign'      => 1,
        ]);
    
    $user->cards()->attach([2, 4, 9, 13, 21]);
    $achievementService = app(AchievementService::class);
    $achievementService->addProgress($user, 'COLLECTOR_10', 5);

}

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
