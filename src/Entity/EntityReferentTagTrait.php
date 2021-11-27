<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @deprecated Use {@see App\Entity\EntityZoneTrait} instead
 */
trait EntityReferentTagTrait
{
    /**
     * @var Collection|ReferentTag[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ReferentTag")
     */
    protected $referentTags;

    /**
     * @return Collection|ReferentTag[]
     */
    public function getReferentTags(): Collection
    {
        return $this->referentTags;
    }

    public function addReferentTag(ReferentTag $referentTag): void
    {
        if (!$this->referentTags->contains($referentTag)) {
            $this->referentTags->add($referentTag);
        }
    }

    public function removeReferentTag(ReferentTag $referentTag): void
    {
        $this->referentTags->removeElement($referentTag);
    }

    public function clearReferentTags(): void
    {
        $this->referentTags->clear();
    }

    /**
     * @Groups({"committee_read", "event_read"})
     * @SerializedName("tags")
     */
    public function getReferentTagsCodes(): array
    {
        return array_map(function (ReferentTag $referentTag) {
            return $referentTag->getCode();
        }, $this->referentTags->toArray());
    }
}
