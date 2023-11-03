<?php

namespace App\Donation\Handler;

use App\Donation\Command\ReceivePayboxIpnResponseCommand;
use App\Entity\Donation;
use App\Mailchimp\Synchronisation\Command\AdherentChangeCommand;
use App\Mailer\MailerService;
use App\Mailer\Message\DonationThanksMessage;
use App\Membership\MembershipRequestHandler;
use App\Repository\DonationRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ReceivePayboxIpnResponseCommandHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly MailerService $transactionalMailer,
        private readonly EntityManagerInterface $manager,
        private readonly TransactionRepository $transactionRepository,
        private readonly DonationRepository $donationRepository,
        private readonly MembershipRequestHandler $membershipRequestHandler,
        private readonly MessageBusInterface $bus,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(ReceivePayboxIpnResponseCommand $command): void
    {
        $payload = $command->payload;

        if (!$donation = $this->getDonation($payload)) {
            $this->logger->error('[IPN] Donation not found', ['payload' => $payload]);

            return;
        }

        if ($this->transactionRepository->findByPayboxTransactionId($payload['transaction'])) {
            $this->logger->error('[IPN] Transaction already exists', ['payload' => $payload]);

            return;
        }

        $adherent = $donation->getDonator()?->getAdherent();

        $transaction = $donation->processPayload($payload);

        if ($transaction->isSuccessful()) {
            $donation->markAsLastSuccessfulDonation();
        }

        $this->manager->persist($transaction);
        $this->manager->flush();

        if ($transaction->isSuccessful()) {
            if ($donation->isMembership()) {
                if ($adherent) {
                    if ($donation->isReAdhesion()) {
                        $this->membershipRequestHandler->finishRenaissanceReAdhesion($adherent);
                    } else {
                        $this->membershipRequestHandler->finishRenaissanceAdhesion($adherent);
                    }
                } else {
                    $this->logger->error('Adhesion RE: adherent introuvable pour une cotisation réussie, donation id '.$donation->getId());
                }
            } else {
                $this->transactionalMailer->sendMessage(DonationThanksMessage::createFromTransaction($transaction));
            }

            if ($adherent) {
                $this->bus->dispatch(new AdherentChangeCommand($adherent->getUuid(), $adherent->getEmailAddress()));
            }
        }
    }

    private function getDonation(array $payload): ?Donation
    {
        $donationUuid = isset($payload['id']) ? explode('_', $payload['id'], 2)[0] : null;

        if (!$donationUuid || !Uuid::isValid($donationUuid)) {
            return null;
        }

        return $this->donationRepository->findOneByUuid($donationUuid);
    }
}