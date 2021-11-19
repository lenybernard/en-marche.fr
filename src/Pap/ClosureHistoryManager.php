<?php

namespace App\Pap;

use App\Entity\Adherent;
use App\Entity\Pap\Building;
use App\Entity\Pap\BuildingStatistics;
use App\Entity\Pap\Campaign;
use App\Entity\Pap\CampaignStatisticsOwnerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ClosureHistoryManager
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function open(
        CampaignStatisticsOwnerInterface $objet,
        Campaign $campaign,
        \DateTimeInterface $openedAt,
        Adherent $openedBy
    ): void {
        $this->openClose(BuildingStatusEnum::ONGOING, $objet, $campaign, $openedAt, $openedBy);
    }

    public function close(
        CampaignStatisticsOwnerInterface $objet,
        Campaign $campaign,
        \DateTimeInterface $closedAt,
        Adherent $closedBy
    ): void {
        $this->openClose(BuildingStatusEnum::COMPLETED, $objet, $campaign, $closedAt, $closedBy);
    }

    public function openClose(
        string $status,
        CampaignStatisticsOwnerInterface $objet,
        Campaign $campaign,
        \DateTimeInterface $doneAt,
        Adherent $doneBy
    ): void {
        $stats = $objet->getStatisticForCampaign($campaign);

        if (!$stats) {
            switch (\get_class($objet)) {
                case Building::class:
                    $stats = new BuildingStatistics($objet, $campaign, $status);

                    return;
            }

            $this->em->persist($stats);
        }

        $stats->setStatus($status);
        if (BuildingStatusEnum::COMPLETED === $stats) {
            $stats->setClosedAt($doneAt);
            $stats->setClosedBy($doneBy);
        }

        $this->em->flush();
    }
}
