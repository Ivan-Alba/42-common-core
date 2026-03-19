<?php

namespace App\Enums;

enum CardRarity: string
{
    case COMMON = "Common";
    case RARE = "Rare";
    case EPIC = "Epic";
    case LEGENDARY = "Legendary";

public function getCost(): int
{
    return match($this) {
        self::COMMON => 0,
        self::RARE => 1,
        self::EPIC => 2,
        self::LEGENDARY => 3,
        default => 0,
    };
}

}
