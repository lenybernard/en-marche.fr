<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OAuthClientExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_jme_client_id', [OAuthClientRuntime::class, 'getJMEClientId']),
            new TwigFunction('get_vox_client_id', [OAuthClientRuntime::class, 'getVoxClientId']),
        ];
    }
}
