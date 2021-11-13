<?php

namespace App\Serializer;

use JMS\Serializer\AbstractVisitor;
use Sabre\VObject\Component\VCalendar;

class IcalSerializationVisitor extends AbstractVisitor
{
    /**
     * @return string|null
     */
    public function getResult($data)
    {
        if (!$root = $this->getRoot()) {
            return;
        }

        return (new VCalendar($root))->serialize();
    }
}
