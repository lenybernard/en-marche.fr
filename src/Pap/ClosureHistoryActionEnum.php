<?php

namespace App\Pap;

use MyCLabs\Enum\Enum;

class ClosureHistoryActionEnum extends Enum
{
    public const OPEN = 'open';
    public const CLOSE = 'close';

    public const URI_PATTERN = self::OPEN.'|'.self::CLOSE;
}
