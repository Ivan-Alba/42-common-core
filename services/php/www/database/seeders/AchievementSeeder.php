<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Card;
use App\Enums\AchievementCategory;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // We define the base achievements.
        // card_reward_id now points directly to the auto-incremental ID in the 'cards' table.
        $achievements = [
            [
                'code' => 'FIRST_WIN',
                'category' => AchievementCategory::BRONZE,
                'goal' => 1,
                'points' => 50,
                'card_reward_id' => null
            ],
            [
                'code' => 'WIN_10_MATCHES',
                'category' => AchievementCategory::SILVER,
                'goal' => 10,
                'points' => 200,
                'card_reward_id' => 15
            ],
            [
                'code' => 'COLLECTOR_10',
                'category' => AchievementCategory::BRONZE,
                'goal' => 10,
                'points' => 100,
                'card_reward_id' => null
            ],
            [
                'code' => 'PERFECT_MATCH',
                'category' => AchievementCategory::GOLD,
                'goal' => 1,
                'points' => 500,
                'card_reward_id' => 22
            ],
            [
                'code' => 'SOCIAL_BUTTERFLY',
                'category' => AchievementCategory::BRONZE,
                'goal' => 5,
                'points' => 50,
                'card_reward_id' => null
            ],
            [
                'code' => 'VETERAN_PLAYER',
                'category' => AchievementCategory::DIAMOND,
                'goal' => 100,
                'points' => 1000,
                'card_reward_id' => 24
            ],
            [
                'code' => 'STREAK_3',
                'category' => AchievementCategory::SILVER,
                'goal' => 3,
                'points' => 150,
                'card_reward_id' => null
            ],
            [
                'code' => 'LEVEL_10',
                'category' => AchievementCategory::SILVER,
                'goal' => 1,
                'points' => 300,
                'card_reward_id' => 18
            ],
            [
                'code' => 'DESTRUCTOR',
                'category' => AchievementCategory::SILVER,
                'goal' => 50,
                'points' => 250,
                'card_reward_id' => 21
            ],
            [
                'code' => 'NIGHT_OWL',
                'category' => AchievementCategory::BRONZE,
                'goal' => 1,
                'points' => 100,
                'card_reward_id' => null
            ],
        ];

        foreach ($achievements as $data) {
            // Check if the card ID exists before assigning it (optional but recommended for safety)
            $finalCardId = null;
            if ($data['card_reward_id']) {
                if (Card::where('id', $data['card_reward_id'])->exists()) {
                    $finalCardId = $data['card_reward_id'];
                } else {
                    $this->command->warn("Card ID {$data['card_reward_id']} not found in database. Setting reward to null for {$data['code']}.");
                }
            }

            Achievement::updateOrCreate(
                ['code' => $data['code']],
                [
                    'category' => $data['category'],
                    'goal' => $data['goal'],
                    'points' => $data['points'],
                    'card_reward_id' => $finalCardId,
                ]
            );
        }

        $this->command->info('Achievements seeded successfully!');
    }
}
