<?php

namespace App\Serializer;

use App\Entity\Event\BaseEvent;
use App\Entity\Event\CommitteeEvent;
use App\Entity\Event\MunicipalEvent;

class EventICalHandler
{
    public static function getSubscribingMethods(): array
    {
        return [
//            [
//                'type' => CommitteeEvent::class,
//                'format' => 'ical',
//                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
//                'method' => 'serialize',
//            ],
//            [
//                'type' => MunicipalEvent::class,
//                'format' => 'ical',
//                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
//                'method' => 'serialize',
//            ],
        ];
    }

    public function serialize(IcalSerializationVisitor $visitor, BaseEvent $event): void
    {
        $eventData = [
            'VEVENT' => [
                'UID' => $event->getUuid()->toString(),
                'SUMMARY' => $event->getName(),
                'DESCRIPTION' => $event->getDescription(),
                'DTSTART' => $event->getLocalBeginAt(),
                'DTEND' => $event->getLocalFinishAt(),
                'LOCATION' => $event->getInlineFormattedAddress(),
            ],
        ];

        if ($organizer = $event->getOrganizer()) {
            $eventData['ORGANIZER'] = sprintf(
                '%s %s',
                $organizer->getFirstName(),
                mb_strtoupper($organizer->getLastName())
            );
        }

        //$visitor->setRoot($eventData);
    }
}
