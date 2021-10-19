<?php

namespace App\Scope\Generator;

use App\Entity\Adherent;
use App\Scope\ScopeEnum;

class DoorToDoorNationalManagerScopeGenerator extends AbstractScopeGenerator
{
    public function getCode(): string
    {
        return ScopeEnum::DOOR_TO_DOOR_NATIONAL_MANAGER;
    }

    public function supports(Adherent $adherent): bool
    {
        return $adherent->hasDoorToDoorNationalManagerRole();
    }

    protected function getZones(Adherent $adherent): array
    {
        return [];
    }
}
