<?php

namespace App\ManagedUsers;

use App\Subscription\SubscriptionTypeEnum;

class ManagedUsersFilterFactory
{
    public static function createForZones(string $scopeCode, array $zones): ?ManagedUsersFilter
    {
        return new ManagedUsersFilter(
            SubscriptionTypeEnum::SUBSCRIPTION_TYPES_BY_SCOPES[$scopeCode] ?? null,
            $zones
        );
    }
}
