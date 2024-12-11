<?php

namespace App\Entity\ProcurationV2;

use App\Procuration\V2\ProcurationActionStatusEnum;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\MappedSuperclass]
abstract class AbstractProcurationAction extends AbstractAction
{
    #[Groups(['procuration_request_read', 'procuration_request_list', 'procuration_proxy_list', 'procuration_matched_proxy', 'procuration_proxy_slot_read', 'procuration_request_slot_read'])]
    #[ORM\Column(enumType: ProcurationActionStatusEnum::class)]
    public ProcurationActionStatusEnum $status;

    public function __construct(UuidInterface $uuid, \DateTimeInterface $date, ProcurationActionStatusEnum $status)
    {
        parent::__construct($uuid, $date);

        $this->status = $status;
    }
}
