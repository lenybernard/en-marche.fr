<?php

namespace App\Serializer;

use App\Entity\Mooc\Mooc;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 *
 */
class MoocSerializerSubscriber
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            //['event' => Events::POST_SERIALIZE, 'method' => 'onPostSerialize', 'class' => Mooc::class],
        ];
    }

    public function onPostSerialize($event): void
    {
        if (\in_array('mooc_list', $event->getContext()->attributes->get('groups')->get(), true)) {
            /** @var Mooc $mooc */
            $mooc = $event->getObject();

            $event->getVisitor()->addData(
                'image',
                $mooc->getListImage()
                    ? $this->urlGenerator->generate('asset_url', ['path' => $mooc->getListImage()->getFilePath()], UrlGeneratorInterface::ABSOLUTE_URL)
                    : $mooc->getYoutubeThumbnail()
            );
        }
    }
}
