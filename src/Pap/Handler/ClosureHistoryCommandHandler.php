<?php

namespace App\Pap\Handler;

use App\Entity\Pap\ClosureHistory;
use App\Pap\ClosureHistoryManager;
use App\Pap\Command\ClosureHistoryCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ClosureHistoryCommandHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private ClosureHistoryManager $closureHistoryManager;

    public function __construct(EntityManagerInterface $entityManager, ClosureHistoryManager $closureHistoryManager)
    {
        $this->entityManager = $entityManager;
        $this->closureHistoryManager = $closureHistoryManager;
    }

    public function __invoke(ClosureHistoryCommand $command): void
    {
        /** @var ClosureHistory $closureHistory */
        if (!$closureHistory = $this->entityManager->find(ClosureHistory::class, $command->getClosureHistory())) {
            return;
        }

        if ($closureHistory->isOpenAction()) {
            $this->closureHistoryManager->open(
                $closureHistory->getBuildingBlock() ?? $closureHistory->getFloor(),
                $closureHistory->getCampaign(),
                $closureHistory->getCreatedAt(),
                $closureHistory->getCreatedBy()
            );
        } elseif ($closureHistory->isCloseAction()) {
            $this->closureHistoryManager->close(
                $closureHistory->getBuildingBlock() ?? $closureHistory->getFloor(),
                $closureHistory->getCampaign(),
                $closureHistory->getCreatedAt(),
                $closureHistory->getCreatedBy()
            );
        }

        $this->entityManager->flush();
    }
}
