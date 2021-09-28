<?php

namespace App\Normalizer;

use App\AdherentSpace\AdherentSpaceEnum;
use App\Entity\AdherentMessage\Filter\AudienceFilter;
use App\Geo\ManagedZoneProvider;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AudienceFilterDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const AUDIENCE_FILTER_DENORMALIZER_ALREADY_CALLED = 'AUDIENCE_FILTER_DENORMALIZER_ALREADY_CALLED';

    private ManagedZoneProvider $managedZoneProvider;
    private Security $security;

    public function __construct(ManagedZoneProvider $managedZoneProvider, Security $security)
    {
        $this->managedZoneProvider = $managedZoneProvider;
        $this->security = $security;
    }

    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $context[self::AUDIENCE_FILTER_DENORMALIZER_ALREADY_CALLED] = true;

        /** @var AudienceFilter $audienceFilter */
        $audienceFilter = $this->denormalizer->denormalize($data, $type, $format, $context);

        if (($user = $this->security->getUser()) && $audienceFilter->getScope() && AdherentSpaceEnum::SCOPES[$audienceFilter->getScope()]) {
            $audienceFilter->setZones($this->managedZoneProvider->getManagedZones(
                $user,
                AdherentSpaceEnum::SCOPES[$audienceFilter->getScope()]
            ));
        }

        return $audienceFilter;
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return
            empty($context[self::AUDIENCE_FILTER_DENORMALIZER_ALREADY_CALLED])
            && AudienceFilter::class === $type;
    }
}
