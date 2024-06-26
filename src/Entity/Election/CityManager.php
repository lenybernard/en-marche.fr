<?php

namespace App\Entity\Election;

use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'election_city_manager')]
#[ORM\Entity]
class CityManager
{
    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    #[ORM\Column]
    private $name;

    /**
     * @var PhoneNumber|null
     *
     * @AssertPhoneNumber
     */
    #[ORM\Column(type: 'phone_number', nullable: true)]
    private $phone;

    public function __construct(?string $name = null, ?PhoneNumber $phone = null)
    {
        $this->name = $name;
        $this->phone = $phone;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPhone(): ?PhoneNumber
    {
        return $this->phone;
    }

    public function setPhone(?PhoneNumber $phone): void
    {
        $this->phone = $phone;
    }

    public function isEmpty(): bool
    {
        return !$this->name;
    }
}
