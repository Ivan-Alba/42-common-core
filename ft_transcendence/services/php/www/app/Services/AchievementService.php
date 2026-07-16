<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Models\Card;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\GameMode;

class AchievementService
{
    /**
     * Main entry point to check achievements after a match ends.
     */
    public function checkMatchAchievements(int $userId, array $matchData): void
    {
        $user = User::find($userId);
        if (!$user || $user->is_bot)
            return;

        // 1. Win-related achievements
        if ($matchData['is_winner']) {

            // First win should be against a human (not PVE)
            if (!$this->isPveMode($matchData['game_mode'])) {
                $this->addProgress($user, 'FIRST_WIN', 1);
                $this->addProgress($user, 'STREAK_3', 1);
            }

            $this->addProgress($user, 'WIN_10_MATCHES', 1);

            // Perfect Match check (opponent didn't flip any of your cards or tie)
            if ($matchData['opponent_score'] === 1) {
                $this->addProgress($user, 'PERFECT_MATCH', 1);
            }

            // Destructor: cards flipped from the opponent.
            // (Total 5 cards - opponent's final cards = cards you took)
            if ($matchData['opponent_score'] < 5) {
                $this->addProgress($user, 'DESTRUCTOR', 5 - $matchData['opponent_score']);
            }
        } else {
            // If they lose, reset the win streak
            if (!$this->isPveMode($matchData['game_mode'])) {
                $this->resetProgress($userId, 'STREAK_3');
            }
        }

        // 2. Volume-related achievements
        $this->addProgress($user, 'VETERAN_PLAYER', 1);

        // 3. Night Owl: check current time (late night/early morning)
        $hour = now()->hour;
        if ($hour >= 0 && $hour <= 5) {
            $this->addProgress($user, 'NIGHT_OWL', 1);
        }
    }

    /**
     * Resets the progress of a specific achievement for a user.
     */
    public function resetProgress(int $userId, string $code): void
    {
        $achievement = Achievement::where('code', $code)->first();
        if (!$achievement)
            return;

        $user = User::find($userId);
        if (!$user)
            return;

        // We only reset if the achievement is NOT already unlocked.
        // Usually, once you earn a trophy, you don't lose it even if you lose a match.
        $userAchievement = $user->achievements()->where('achievement_id', $achievement->id)->first();

        if ($userAchievement && is_null($userAchievement->pivot->unlocked_at)) {
            $user->achievements()->updateExistingPivot($achievement->id, [
                'progress' => 0
            ]);

            Log::info("[Achievement] Progress reset for user {$userId} on achievement {$code}");
        }
    }

    /**
     * Check social achievements when a friend is added.
     */
    public function checkSocialAchievements(User $user): void
    {
        // Placeholder for actual relationship count
        $friendsCount = 5;
        if ($friendsCount >= 5) {
            $this->addProgress($user, 'SOCIAL_BUTTERFLY', 5);
        }
    }

    /**
     * Core logic to update pivot table progress.
     */
    public function addProgress(User $user, string $code, int $amount): void
    {
        $achievement = Achievement::where('code', $code)->first();
        if (!$achievement)
            return;

        $userAchievement = $user->achievements()->where('achievement_id', $achievement->id)->first();

        $currentProgress = $userAchievement ? $userAchievement->pivot->progress : 0;
        $alreadyUnlocked = $userAchievement ? !is_null($userAchievement->pivot->unlocked_at) : false;

        if ($alreadyUnlocked)
            return;

        $newProgress = $currentProgress + $amount;
        $isNowUnlocked = $newProgress >= $achievement->goal;

        $user->achievements()->syncWithoutDetaching([
            $achievement->id => [
                'progress' => min($newProgress, $achievement->goal),
                'unlocked_at' => $isNowUnlocked ? now() : null,
            ]
        ]);
    }

    /**
     * Endpoint to claim an achievement reward.
     */
    public function claimReward(User $user, int $achievementId): array
    {
        // Searcgh for the achievement in the user's achievements
        $achievement = $user->achievements()
            ->where('achievement_id', $achievementId)
            ->first();

        // Security validations
        if (!$achievement) {
            return ['success' => false, 'message' => 'Achievement not found for this user.'];
        }

        if (!$achievement->pivot->unlocked_at) {
            return ['success' => false, 'message' => 'Achievement is not unlocked yet.'];
        }

        if ($achievement->pivot->claimed) {
            return ['success' => false, 'message' => 'Reward already claimed.'];
        }

        try {
            DB::transaction(function () use ($user, $achievement) {
                // 1. Mark as claimed in the DB
                $user->achievements()->updateExistingPivot($achievement->id, [
                    'claimed' => true
                ]);

                // 2. Grant rewards (XP and Cards)
                $this->grantReward($user, $achievement);
            });

            return [
                'success' => true,
                'message' => 'Reward claimed successfully!',
                'points' => $achievement->points
            ];

        } catch (\Exception $e) {
            Log::error("[Achievement] Error claiming reward: " . $e->getMessage());
            return ['success' => false, 'message' => 'Internal server error.'];
        }
    }

    /**
     * Grants the reward associated with the achievement.
     */
    private function grantReward(User $user, Achievement $achievement): void
    {
        // 1. Grant Points
        $user->stats->increment('achievement_points', $achievement->points);

        // 2. Grant Card Reward if exists (Logic to be implemented in user_cards table)
        if ($achievement->card_reward_id) {
            $user->cards()->syncWithoutDetaching([$achievement->card_reward_id]);
            $this->addProgress($user, 'COLLECTOR_10', 1);
            Log::info("User {$user->id} rewarded with card {$achievement->card_reward_id}");
        }

        Log::info("Achievement Unlocked: {$achievement->code} for User {$user->id}");
    }

    private function isPveMode(GameMode $mode): bool
    {
        return in_array($mode, [
            GameMode::CAMPAIGN_1,
            GameMode::CAMPAIGN_2,
            GameMode::CAMPAIGN_3,
            GameMode::CAMPAIGN_4,
            GameMode::PVE
        ]);
    }
}
