<?php

namespace App\Enums;

enum CardRarity: string
{
    case COMMON = "Common";
    case RARE = "Rare";
    case EPIC = "Epic";
    case LEGENDARY = "Legendary";
}
