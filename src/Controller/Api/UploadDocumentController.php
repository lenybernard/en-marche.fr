<?php

namespace App\Controller\Api;

use League\Flysystem\FilesystemOperator;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression("is_granted('REQUEST_SCOPE_GRANTED', ['messages'])"))]
#[Route('/v3/upload-file', methods: ['POST'])]
class UploadDocumentController extends AbstractController
{
    public function __invoke(UserInterface $user, Request $request, FilesystemOperator $publicUserFileStorage, string $secret): Response
    {
        $file = $request->files->get('file');

        if (!$file instanceof UploadedFile) {
            return $this->json(['error' => 'Invalid file'], Response::HTTP_BAD_REQUEST);
        }

        $uuid = Uuid::uuid4()->toString();

        $filePath = hash('sha256', $user->getUuid().$secret).'/'.$uuid.'.'.$file->getClientOriginalExtension();

        $publicUserFileStorage->write($filePath, $file->getContent());

        return $this->json(['url' => $publicUserFileStorage->publicUrl($filePath)], Response::HTTP_CREATED);
    }
}
