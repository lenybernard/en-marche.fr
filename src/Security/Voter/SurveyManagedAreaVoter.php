<?php

namespace App\Security\Voter;

use App\Entity\Adherent;
use App\Entity\Jecoute\LocalSurvey;
use App\Entity\MyTeam\DelegatedAccess;
use Symfony\Component\HttpFoundation\RequestStack;

class SurveyManagedAreaVoter extends AbstractAdherentVoter
{
    public const PERMISSION = 'IS_SURVEY_MANAGER_OF';

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    protected function doVoteOnAttribute(string $attribute, Adherent $adherent, $subject): bool
    {
        if ($delegatedAccess = $adherent->getReceivedDelegatedAccessByUuid($this->requestStack->getSession()->get(DelegatedAccess::ATTRIBUTE_KEY))) {
            $adherent = $delegatedAccess->getDelegator();
        }

        /** @var LocalSurvey $subject */
        $surveyZone = $subject->getZone();

        if ($adherent->isJecouteManager()) {
            $managedZone = $adherent->getJecouteManagedArea()->getZone();
        }

        if ($adherent->isCandidate()) {
            $managedZone = $adherent->getCandidateManagedArea()->getZone();
        }

        if (isset($managedZone)) {
            return $surveyZone === $managedZone
                || ($managedZone->hasChild($surveyZone) || $managedZone->hasParent($surveyZone));
        }

        return false;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return self::PERMISSION === $attribute && $subject instanceof LocalSurvey;
    }
}
