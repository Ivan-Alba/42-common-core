<?php

namespace App\Services;

use App\Enums\GameMode;

class MatchConfigProvider
{
    /**
     * Returns the static configuration for a given GameMode.
     * Use this to sync rules between Laravel and Unity.
     * * @param GameMode $mode
     * @return array
     */
    public static function getConfig(GameMode $mode): array
    {
        return match ($mode) {
            GameMode::PVP_RANKED => [
                'board_size' => 3,
                'hand_size' => 5,
                'turn_time_limit' => 30,
                'selection_time_limit' => 45,
                'max_deck_cost' => 6,
                'rules' => ['open', 'same', 'plus', 'combo'],
                'rewards' => [
                    'win_xp' => 150,
                    'draw_xp' => 100,
                    'loss_xp' => 50,
                    'win_ranked_points' => 50,
                    'draw_ranked_points' => 0,
                    'loss_ranked_points' => -20
                ]
            ],
            GameMode::PVP_CASUAL_LIMITED => [
                'board_size' => 3,
                'hand_size' => 5,
                'turn_time_limit' => 60,
                'selection_time_limit' => 45,
                'max_deck_cost' => 6,
                'rules' => ['open', 'same', 'plus', 'combo'],
                'rewards' => [
                    'win_xp' => 120,
                    'draw_xp' => 75,
                    'loss_xp' => 30,
                    'win_ranked_points' => 0,
                    'draw_ranked_points' => 0,
                    'loss_ranked_points' => 0
                ]
            ],
            GameMode::PVP_CASUAL_UNLIMITED => [
                'board_size' => 3,
                'hand_size' => 5,
                'turn_time_limit' => 60,
                'selection_time_limit' => 45,
                'max_deck_cost' => 99, // no limit
                'rules' => ['open', 'same', 'plus', 'combo'],
                'rewards' => [
                    'win_xp' => 120,
                    'draw_xp' => 75,
                    'loss_xp' => 30,
                    'win_ranked_points' => 0,
                    'draw_ranked_points' => 0,
                    'loss_ranked_points' => 0
                ]
            ],
            GameMode::CAMPAIGN_1 => [
                'board_size' => 3,
                'hand_size' => 5,
                'turn_time_limit' => 60,
                'selection_time_limit' => 60,
                'max_deck_cost' => 5,
                'rules' => ['open'],
                'rewards' => [
                    'win_xp' => 80,
                    'draw_xp' => 50,
                    'loss_xp' => 20,
                    'win_ranked_points' => 0,
                    'draw_ranked_points' => 0,
                    'loss_ranked_points' => 0
                ]
            ],

            default => self::getDefaultConfig(),
        };
    }

    private static function getDefaultConfig(): array
    {
        return [
            'board_size' => 3,
            'hand_size' => 5,
            'turn_time_limit' => 10,
            'selection_time_limit' => 15,
            'max_deck_cost' => 6,
            'rules' => ['open'],
            'rewards' => [
                'win_xp' => 80,
                'draw_xp' => 50,
                'loss_xp' => 20,
                'win_ranked_points' => 0,
                'draw_ranked_points' => 0,
                'loss_ranked_points' => 0
            ]
        ];
    }
}
