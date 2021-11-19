<?php

namespace App\Entity\Pap;

use App\Entity\Adherent;
use App\Entity\EntityIdentityTrait;
use App\Pap\ClosureHistoryActionEnum;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pap_closure_history")
 */
class ClosureHistory
{
    use EntityIdentityTrait;

    /**
     * @ORM\Column(length=10)
     *
     * @Assert\Choice(
     *     callback={"App\Pap\ClosureHistoryActionEnum", "toArray"},
     *     strict=true
     * )
     */
    private string $action;

    /**
     * @Assert\NotNull
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Pap\Campaign")
     * @ORM\JoinColumn(nullable=false)
     */
    private Campaign $campaign;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pap\Building")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ?BuildingBlock $building = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pap\BuildingBlock")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ?BuildingBlock $buildingBlock = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pap\Floor")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ?Floor $floor = null;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Adherent")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private Adherent $createdBy;

    public function __construct(string $action, Campaign $campaign, Adherent $createdBy)
    {
        $this->uuid = Uuid::uuid4();
        $this->action = $action;
        $this->campaign = $campaign;
        $this->createdBy = $createdBy;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(Campaign $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(Building $building): void
    {
        $this->building = $building;
    }

    public function getBuildingBlock(): ?BuildingBlock
    {
        return $this->buildingBlock;
    }

    public function setBuildingBlock(BuildingBlock $buildingBlock): void
    {
        $this->buildingBlock = $buildingBlock;
    }

    public function getFloor(): ?Floor
    {
        return $this->floor;
    }

    public function setFloor(Floor $floor): void
    {
        $this->floor = $floor;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedBy(): ?Adherent
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Adherent $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function isOpenAction(): bool
    {
        return ClosureHistoryActionEnum::OPEN === $this->action;
    }

    public function isCloseAction(): bool
    {
        return ClosureHistoryActionEnum::CLOSE === $this->action;
    }
}
