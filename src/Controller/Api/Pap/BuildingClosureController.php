<?php

namespace App\Controller\Api\Pap;

use App\Entity\Pap\BuildingBlock;
use App\Entity\Pap\Campaign;
use App\Entity\Pap\CampaignStatisticsOwnerInterface;
use App\Entity\Pap\ClosureHistory;
use App\Entity\Pap\Floor;
use App\Pap\Command\ClosureHistoryCommand;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Entity("campaign", expr="repository.findOneByUuid(campaign_uuid)")
 * @Security("is_granted('ROLE_OAUTH_SCOPE_JEMARCHE_APP') and is_granted('ROLE_ADHERENT')")
 */
class BuildingClosureController extends AbstractController
{
    private EntityManagerInterface $em;
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $bus)
    {
        $this->em = $em;
        $this->bus = $bus;
    }

    /**
     * @Route("/v3/pap/building_blocks/{uuid}/{action}_for_campaign/{campaign_uuid}",
     *     requirements={
     *         "uuid": "%pattern_uuid%",
     *         "action": App\Pap\ClosureHistoryActionEnum::URI_PATTERN,
     *         "campaign_uuid": "%pattern_uuid%"
     *     },
     *     name="api_close_open_building_block",
     *     methods={"POST"}
     * )
     */
    public function closeOpenBuildingBlock(
        string $action,
        BuildingBlock $buildingBlock,
        Campaign $campaign
    ): JsonResponse {
        return $this->closeOpen($action, $buildingBlock, $campaign);
    }

    /**
     * @Route("/v3/pap/floors/{uuid}/{action}_for_campaign/{campaign_uuid}",
     *     requirements={
     *         "uuid": "%pattern_uuid%",
     *         "action": App\Pap\ClosureHistoryActionEnum::URI_PATTERN,
     *         "campaign_uuid": "%pattern_uuid%"
     *     },
     *     name="api_close_open_floor",
     *     methods={"POST"}
     * )
     */
    public function closeOpenFloor(string $action, Floor $floor, Campaign $campaign): JsonResponse
    {
        return $this->closeOpen($action, $floor, $campaign);
    }

    private function closeOpen(
        string $action,
        CampaignStatisticsOwnerInterface $object,
        Campaign $campaign
    ): JsonResponse {
        $closure = new ClosureHistory($action, $campaign, $this->getUser());
        if ($object instanceof BuildingBlock) {
            $closure->setBuildingBlock($object);
        } elseif ($object instanceof Floor) {
            $closure->setFloor($object);
        } else {
            return $this->json([
                'code' => 'unknown_object',
                'message' => 'Impossible d\'ouvrir ou fermer ce type d\'objet.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->em->persist($closure);
        $this->em->flush();

        $this->bus->dispatch(new ClosureHistoryCommand($closure));

        return $this->json('OK');
    }
}
