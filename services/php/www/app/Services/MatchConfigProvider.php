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
            ],
            GameMode::PVP_CASUAL_LIMITED => [
                'board_size' => 3,
                'hand_size' => 5,
                'turn_time_limit' => 60,
                'selection_time_limit' => 45,
                'max_deck_cost' => 6,
                'rules' => ['open', 'same', 'plus', 'combo'],
            ],
            GameMode::PVP_CASUAL_UNLIMITED => [
                'board_size' => 3,
                'hand_size' => 5,
                'turn_time_limit' => 60,
                'selection_time_limit' => 45,
                'max_deck_cost' => 99, // no limit
                'rules' => ['open', 'same', 'plus', 'combo'],
            ],
            GameMode::CAMPAIGN_1 => [
                'board_size' => 3,
                'hand_size' => 5,
                'turn_time_limit' => 60,
                'selection_time_limit' => 60,
                'max_deck_cost' => 5,
                'rules' => ['open'],
            ],

            default => self::getDefaultConfig(),
        };
    }

    private static function getDefaultConfig(): array
    {
        return [
            'board_size' => 3,
            'hand_size' => 5,
            'turn_time_limit' => 60,
            'selection_time_limit' => 45,
            'max_deck_cost' => 6,
            'rules' => ['open'],
        ];
    }
}
