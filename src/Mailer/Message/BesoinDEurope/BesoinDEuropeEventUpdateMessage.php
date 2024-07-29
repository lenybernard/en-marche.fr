<?php

namespace App\Mailer\Message\BesoinDEurope;

use App\Entity\Adherent;
use App\Entity\Event\BaseEvent;
use App\Entity\Event\EventRegistration;
use Ramsey\Uuid\Uuid;

class BesoinDEuropeEventUpdateMessage extends AbstractBesoinDEuropeMessage
{
    public static function create(array $recipients, Adherent $host, BaseEvent $event, string $eventUrl): self
    {
        if (!$recipients) {
            throw new \InvalidArgumentException('At least one Adherent recipient is required.');
        }

        $recipient = array_shift($recipients);

        $message = new self(
            Uuid::uuid4(),
            $recipient->getEmailAddress(),
            $recipient->getFirstName().' '.$recipient->getLastName(),
            '',
            static::getTemplateVars($event, $eventUrl),
            static::getRecipientVars($recipient)
        );

        /* @var EventRegistration[] $recipients */
        foreach ($recipients as $recipient) {
            $message->addRecipient(
                $recipient->getEmailAddress(),
                $recipient->getFirstName().' '.$recipient->getLastName(),
                static::getRecipientVars($recipient)
            );
        }

        return $message;
    }

    private static function getTemplateVars(BaseEvent $event, string $eventUrl): array
    {
        return [
            'event_name' => self::escape($event->getName()),
            'event_url' => $eventUrl,
            'event_date' => static::formatDate($event->getLocalBeginAt(), 'EEEE d MMMM y'),
            'event_hour' => \sprintf(
                '%sh%s',
                static::formatDate($event->getLocalBeginAt(), 'HH'),
                static::formatDate($event->getLocalBeginAt(), 'mm')
            ),
            'event_address' => $event->getInlineFormattedAddress(),
        ];
    }

    private static function getRecipientVars(EventRegistration $recipient): array
    {
        return ['first_name' => self::escape($recipient->getFirstName())];
    }
}
