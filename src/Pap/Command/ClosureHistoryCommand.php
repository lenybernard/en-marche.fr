<?php

namespace App\Pap\Command;

use App\Entity\Pap\ClosureHistory;
use App\Messenger\Message\AsynchronousMessageInterface;

class ClosureHistoryCommand implements AsynchronousMessageInterface
{
    private ClosureHistory $closureHistory;

    public function __construct(ClosureHistory $closureHistory)
    {
        $this->closureHistory = $closureHistory;
    }

    public function getClosureHistory(): ClosureHistory
    {
        return $this->closureHistory;
    }
}
