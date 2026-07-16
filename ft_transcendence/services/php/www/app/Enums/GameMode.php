<?php

namespace App\Enums;

enum GameMode: string
{
    case CAMPAIGN_1 = "CAMPAIGN_1";
    case CAMPAIGN_2 = "CAMPAIGN_2";
    case CAMPAIGN_3 = "CAMPAIGN_3";
    case CAMPAIGN_4 = "CAMPAIGN_4";
    case PVE = "PVE";
    case PVP_CASUAL_LIMITED = "PVP_CASUAL_LIMITED";
    case PVP_CASUAL_UNLIMITED = "PVP_CASUAL_UNLIMITED";
    case PVP_RANKED = "PVP_RANKED";
}
