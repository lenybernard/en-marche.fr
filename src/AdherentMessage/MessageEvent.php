<?php

namespace App\AdherentMessage;

use App\Entity\AdherentMessage\AdherentMessageInterface;
use Symfony\Component\EventDispatcher\Event;

class MessageEvent extends Event
{
    private $message;

    public function __construct(AdherentMessageInterface $message)
    {
        $this->message = $message;
    }

    public function getMessage(): AdherentMessageInterface
    {
        return $this->message;
    }
}
