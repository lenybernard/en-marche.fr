<?php

namespace App\Mailer\Message\Renaissance;

use App\Entity\PetitionSignature;
use App\Mailer\Message\Message;
use Ramsey\Uuid\Uuid;

class PetitionConfirmationMessage extends AbstractRenaissanceMessage
{
    public static function create(PetitionSignature $signature, string $url): Message
    {
        return new self(
            Uuid::uuid4(),
            $signature->email,
            $signature->getFullName(),
            'Confirmez votre signature à la pétition',
            [
                'first_name' => $signature->firstName,
                'petition_name' => $signature->petitionName,
                'validation_url' => $url,
            ],
        );
    }
}
