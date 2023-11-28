<?php

namespace Tests\App\Command;

use App\Mailer\Message\Renaissance\RenaissanceNewAdherentsNotificationMessage;
use PHPUnit\Framework\Attributes\Group;
use Tests\App\AbstractCommandTestCase;

#[Group('functional')]
class SendNewMembershipNotificationCommandTest extends AbstractCommandTestCase
{
    public function testCommandSuccess(): void
    {
        $output = $this->runCommand('app:membership:send-notification');

        $output = $output->getDisplay();

        self::assertStringContainsString('Found 3 manager(s) to process about new memberships', $output);

        $this->assertCountMails(1, RenaissanceNewAdherentsNotificationMessage::class, 'adherent-male-55@en-marche-dev.fr');
    }
}
