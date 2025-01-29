<?php

namespace App\Entity;

use App\Renaissance\Petition\SignatureRequest;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
class PetitionSignature
{
    use EntityIdentityTrait;
    use EntityUTMTrait;
    use EntityTimestampableTrait;

    #[ORM\Column]
    public ?string $civility = null;

    #[ORM\Column]
    public ?string $firstName = null;

    #[ORM\Column]
    public ?string $lastName = null;

    #[ORM\Column]
    public ?string $email = null;

    #[ORM\Column]
    public ?string $postalCode = null;

    #[ORM\Column(type: 'phone_number', nullable: true)]
    public ?PhoneNumber $phone = null;

    #[ORM\Column]
    public ?string $petitionName = null;

    #[ORM\Column]
    public ?string $petitionSlug = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    public ?\DateTime $validatedAt = null;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public static function createFromRequest(SignatureRequest $request): self
    {
        $signature = new self();
        $signature->civility = $request->civility;
        $signature->firstName = $request->firstName;
        $signature->lastName = $request->lastName;
        $signature->email = $request->email;
        $signature->postalCode = $request->postalCode;
        $signature->phone = $request->phone;
        $signature->petitionName = $request->petitionName;
        $signature->petitionSlug = $request->petitionSlug;
        $signature->utmSource = $request->utmSource;
        $signature->utmCampaign = $request->utmCampaign;

        return $signature;
    }

    public function getFullName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function validate(): void
    {
        $this->validatedAt = new \DateTime();
    }
}
