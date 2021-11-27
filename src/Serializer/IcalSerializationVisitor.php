<?php

namespace App\Serializer;

use Sabre\VObject\Component\VCalendar;

class IcalSerializationVisitor
{
    /**
     * @return string|null
     */
    public function getResult($data)
    {
//        if (!$root = $this->getRoot()) {
//            return;
//        }

        return (new VCalendar())->serialize();
    }
}
