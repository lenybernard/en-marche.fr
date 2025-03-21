<?php

namespace App\Mailer\Message\Renaissance;

use App\Entity\Adherent;
use App\Entity\Event\Event;
use Ramsey\Uuid\Uuid;

final class RenaissanceEventNotificationMessage extends AbstractRenaissanceMessage
{
    /**
     * Creates a new message instance for a list of recipients.
     *
     * @param Adherent[] $recipients
     */
    public static function create(array $recipients, Adherent $host, Event $event, string $eventLink): self
    {
        if (!$recipients) {
            throw new \InvalidArgumentException('At least one Adherent recipient is required.');
        }

        $recipient = array_shift($recipients);
        if (!$recipient instanceof Adherent) {
            throw new \RuntimeException('First recipient must be an Adherent instance.');
        }

        $vars = static::getTemplateVars(
            $host->getFirstName(),
            $event->getName(),
            static::formatDate($event->getLocalBeginAt(), 'EEEE d MMMM y'),
            \sprintf(
                '%sh%s',
                static::formatDate($event->getLocalBeginAt(), 'HH'),
                static::formatDate($event->getLocalBeginAt(), 'mm')
            ),
            $event->getInlineFormattedAddress(),
            $eventLink,
            $event->getDescription(),
            $event->getCommittee()?->getName()
        );

        $message = new static(
            Uuid::uuid4(),
            $recipient->getEmailAddress(),
            $recipient->getFullName(),
            \sprintf(
                '%s - %s : Nouvel événement%s : %s',
                static::formatDate($event->getLocalBeginAt(), 'd MMMM'),
                $vars['event_hour'],
                ($committeeName = $event->getCommittee()?->getName()) ? ' de '.$committeeName : '',
                $vars['event_name']
            ),
            $vars,
            static::getRecipientVars($recipient->getFirstName()),
            $host->getEmailAddress()
        );

        foreach ($recipients as $recipient) {
            $message->addRecipient(
                $recipient->getEmailAddress(),
                $recipient->getFullName(),
                static::getRecipientVars($recipient->getFirstName())
            );
        }

        return $message;
    }

    private static function getTemplateVars(
        string $hostFirstName,
        string $eventName,
        string $eventDate,
        string $eventHour,
        string $eventAddress,
        string $eventLink,
        string $eventDescription,
        ?string $committeeName,
    ): array {
        return [
            // Global common variables
            'animator_firstname' => self::escape($hostFirstName),
            'event_name' => self::escape($eventName),
            'event_date' => $eventDate,
            'event_hour' => $eventHour,
            'event_address' => self::escape($eventAddress),
            'event_slug' => $eventLink,
            'event_description' => $eventDescription,
            'committee_name' => $committeeName,
        ];
    }

    public static function getRecipientVars(string $firstName): array
    {
        return [
            'target_firstname' => self::escape($firstName),
        ];
    }
}
