<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStateEnum: string
{
    case AWAITING_PHONE = 'awaiting_phone';
    case AWAITING_CODE = 'awaiting_code';
    case AWAITING_TEST = 'awaiting_test';
}
