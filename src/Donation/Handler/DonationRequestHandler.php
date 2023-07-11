<?php

namespace App\Donation\Handler;

use App\Donation\DonationEvents;
use App\Donation\DonationFactory;
use App\Donation\DonatorFactory;
use App\Donation\DonatorManager;
use App\Donation\Event\DonationWasCreatedEvent;
use App\Donation\Request\DonationRequest;
use App\Entity\Adherent;
use App\Entity\Donation;
use App\Entity\Donator;
use App\Repository\AdherentRepository;
use App\Repository\DonatorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DonationRequestHandler
{
    private $dispatcher;
    private $manager;
    private $donationFactory;
    private $donatorFactory;
    private $donatorRepository;
    private $donatorManager;
    private $adherentRepository;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ManagerRegistry $doctrine,
        DonationFactory $donationFactory,
        DonatorFactory $donatorFactory,
        DonatorRepository $donatorRepository,
        DonatorManager $donatorManager,
        AdherentRepository $adherentRepository
    ) {
        $this->dispatcher = $dispatcher;
        $this->manager = $doctrine->getManagerForClass(Donation::class);
        $this->donationFactory = $donationFactory;
        $this->donatorFactory = $donatorFactory;
        $this->donatorRepository = $donatorRepository;
        $this->donatorManager = $donatorManager;
        $this->adherentRepository = $adherentRepository;
    }

    public function handle(
        DonationRequest $donationRequest,
        Adherent $adherent = null,
        bool $forReAdhesion = false
    ): Donation {
        if (!$donator = $this->donatorRepository->findOneForMatching(
            $donationRequest->getEmailAddress(),
            $donationRequest->getFirstName(),
            $donationRequest->getLastName()
        )) {
            $donator = $this->createDonator($donationRequest);
        }

        if ($adherent && !$donator->isAdherent()) {
            $donator->setAdherent($adherent);
        }

        $donation = $this->donationFactory->createFromDonationRequest($donationRequest, $donator, $forReAdhesion);

        $donator->addDonation($donation);

        $this->dispatcher->dispatch(new DonationWasCreatedEvent($donation), DonationEvents::CREATED);

        $this->manager->persist($donator);
        $this->manager->persist($donation);

        $this->manager->flush();

        return $donation;
    }

    private function createDonator(DonationRequest $donationRequest): Donator
    {
        $donator = $this->donatorFactory->createFromDonationRequest($donationRequest);
        $donator->setIdentifier($this->donatorManager->incrementIdentifier());

        return $donator;
    }
}