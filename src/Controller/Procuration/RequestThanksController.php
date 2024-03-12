<?php

namespace App\Controller\Procuration;

use App\Entity\Procuration\Request as ProcurationRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/mandant/{uuid}/merci', name: 'app_procuration_v2_request_thanks', methods: ['GET'])]
class RequestThanksController extends AbstractController
{
    public function __invoke(ProcurationRequest $procurationRequest): Response
    {
        return $this->render('procuration_v2/request_thanks.html.twig', [
            'procuration_request' => $procurationRequest,
        ]);
    }
}
