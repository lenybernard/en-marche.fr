<?php

namespace App\Scope;

use MyCLabs\Enum\Enum;

class ScopeEnum extends Enum
{
    public const REFERENT = 'referent';
    public const DEPUTY = 'deputy';
    public const SENATOR = 'senator';
    public const CANDIDATE = 'candidate';
    public const NATIONAL = 'national';
    public const PHONING = 'phoning';
    public const PHONING_NATIONAL_MANAGER = 'phoning_national_manager';
    public const DOOR_TO_DOOR_NATIONAL_MANAGER = 'door_to_door_national_manager';

    public const ALL = [
        self::REFERENT,
        self::DEPUTY,
        self::SENATOR,
        self::CANDIDATE,
        self::NATIONAL,
        self::PHONING,
        self::PHONING_NATIONAL_MANAGER,
        self::DOOR_TO_DOOR_NATIONAL_MANAGER,
    ];

    public const FOR_AUDIENCE_SEGMENT = [
        self::REFERENT,
        self::DEPUTY,
        self::SENATOR,
        self::CANDIDATE,
    ];
}
