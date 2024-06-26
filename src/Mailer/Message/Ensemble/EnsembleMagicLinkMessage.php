<?php

namespace App\Mailer\Message\Ensemble;

use App\Entity\Adherent;
use Ramsey\Uuid\Uuid;

class EnsembleMagicLinkMessage extends AbstractEnsembleMessage
{
    public static function create(Adherent $adherent, string $url): self
    {
        return new self(
            Uuid::uuid4(),
            $adherent->getEmailAddress(),
            $adherent->getFullName(),
            'Votre lien de connexion',
            [],
            ['magic_link' => $url],
        );
    }
}
