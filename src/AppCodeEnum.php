<?php

namespace App;

use MyCLabs\Enum\Enum;

final class AppCodeEnum extends Enum
{
    public const PLATFORM = 'platform';
    public const COALITIONS = 'coalitions';
    public const JEMENGAGE_WEB = 'jemengage_web';
    public const JEMENGAGE_MOBILE = 'jemengage_mobile';

    public static function isJeMengageMobileApp(?string $code): bool
    {
        return self::JEMENGAGE_MOBILE === $code;
    }

    public static function isCoalitionsApp(?string $code): bool
    {
        return self::COALITIONS === $code;
    }
}