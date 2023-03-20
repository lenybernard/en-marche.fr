<?php

namespace App\Committee\CommandHandler;

use App\Committee\Command\RefreshCommitteeMembershipsInZoneCommand;
use App\Committee\CommitteeMembershipManager;
use App\Committee\CommitteeMembershipTriggerEnum;
use App\Entity\Committee;
use App\Entity\Geo\Zone;
use App\Repository\AdherentRepository;
use App\Repository\CommitteeRepository;
use App\Repository\Geo\ZoneRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RefreshCommitteeMembershipsInZoneCommandHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly ZoneRepository $zoneRepository,
        private readonly CommitteeRepository $committeeRepository,
        private readonly CommitteeMembershipManager $committeeMembershipManager,
        private readonly AdherentRepository $adherentRepository
    ) {
    }

    public function __invoke(RefreshCommitteeMembershipsInZoneCommand $command): void
    {
        if (!$zone = $this->zoneRepository->findOneByCode($command->getZoneCode())) {
            return;
        }

        $committeesOfZone = $this->findCommitteesForZone($zone);

        [$committeesZones, $zoneCommitteeMapping] = $this->getCommitteesZones($committeesOfZone);

        foreach ($committeesZones as $zones) {
            foreach ($zones as $zone) {
                /** @var Zone $zone */
                $adherents = $this->adherentRepository->findAllForCommitteeZone($zone);

                foreach ($adherents as $adherent) {
                    $this->committeeMembershipManager->followCommittee(
                        $adherent,
                        $committeesOfZone[$zoneCommitteeMapping[$zone->getCode()]],
                        CommitteeMembershipTriggerEnum::COMMITTEE_EDITION
                    );
                }
            }
        }
    }

    /**
     * @return Committee[]
     */
    private function findCommitteesForZone(Zone $zone): array
    {
        $committees = [];
        foreach ($this->committeeRepository->findInZones([$zone]) as $committee) {
            $committees[$committee->getId()] = $committee;
        }

        return $committees;
    }

    /**
     * @param Committee[] $committees
     */
    private function getCommitteesZones(array $committees): array
    {
        $committeesZones = [];
        $zoneCommitteeMapping = [];

        foreach ($committees as $committee) {
            foreach ($committee->getZones() as $zone) {
                if (!isset($committeesZones[$zone->getType()])) {
                    $committeesZones[$zone->getType()] = [];
                }

                $committeesZones[$zone->getType()][] = $zone;
                $zoneCommitteeMapping[$zone->getCode()] = $committee->getId();
            }
        }

        uksort($committeesZones, fn (string $a, string $b) => array_search($a, Zone::COMMITTEE_TYPES) <=> array_search($b, Zone::COMMITTEE_TYPES));

        return [
            $committeesZones,
            $zoneCommitteeMapping,
        ];
    }
}