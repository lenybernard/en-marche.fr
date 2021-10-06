<?php

namespace App\Newsletter;

use App\Validator\Recaptcha as AssertRecaptcha;
use Symfony\Component\Validator\Constraints as Assert;

class NewsletterSubscription
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="neswletter.email.not_blank")
     * @Assert\Email(message="common.email.invalid")
     * @Assert\Length(max=255, maxMessage="common.email.max_length")
     */
    public $email;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min=2,
     *     max=11,
     *     minMessage="neswletter.postalCode.invalid",
     *     maxMessage="neswletter.postalCode.invalid"
     * )
     */
    public $postalCode;

    /**
     * @var string|null
     *
     * @Assert\Country(message="common.country.invalid")
     */
    public $country;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="common.recaptcha.invalid_message")
     * @AssertRecaptcha
     */
    public $recaptcha;

    /**
     * @var bool
     */
    public $personalDataCollection = false;

    public function __construct(string $email = null, string $postalCode = null, string $country = null)
    {
        $this->email = $email;
        $this->postalCode = $postalCode;
        $this->country = $country;
    }

    public static function createWithCaptcha(?string $countryIso, ?string $recaptchaAnswer = null): self
    {
        $dto = new self();
        $dto->recaptcha = $recaptchaAnswer;

        if ($countryIso) {
            $dto->setCountry($countryIso);
        }

        return $dto;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function isPersonalDataCollection(): bool
    {
        return $this->personalDataCollection;
    }

    public function setPersonalDataCollection(bool $personalDataCollection): void
    {
        $this->personalDataCollection = $personalDataCollection;
    }
}
