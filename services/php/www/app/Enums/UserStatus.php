<?php

namespace App\Enums;

enum UserStatus: string
{
    case OFFLINE = 'offline';
    case ONLINE = 'online';
    case PLAYING = 'playing';
}
