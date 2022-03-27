<?php

namespace App\Enums;

enum UserStatus: string
{
    case Pending = 'pending';
    case Verified = 'verified';
    case Active = 'active';
}
