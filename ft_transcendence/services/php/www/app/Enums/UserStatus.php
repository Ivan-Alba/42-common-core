<?php

namespace App\Enums;

enum UserStatus: string
{
    case OFFLINE = 'offline';
    case ONLINE = 'online';
    case QUEUEING = 'queueing';
    case PLAYING = 'playing';
    case AWAY = 'away';
}
