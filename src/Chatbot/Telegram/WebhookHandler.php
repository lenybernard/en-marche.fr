<?php

namespace App\Chatbot\Telegram;

use App\Controller\Webhook\Telegram\ChatbotController;
use App\Entity\Chatbot\Chatbot;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WebhookHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly Client $client
    ) {
    }

    public function deleteWebhook(string $botApiToken): void
    {
        $this->client->deleteWebhook($botApiToken);
    }

    public function handleChanges(Chatbot $chatbot, string $botApiTokenBeforeUpdate = null): void
    {
        $botApiTokenAfterUpdate = $chatbot->telegramBotApiToken;

        if ($botApiTokenAfterUpdate === $botApiTokenBeforeUpdate) {
            return;
        }

        $this->saveSecret($chatbot, $botApiTokenAfterUpdate);

        if (!$botApiTokenAfterUpdate && $botApiTokenBeforeUpdate) {
            $this->deleteWebhook($botApiTokenBeforeUpdate);

            return;
        }

        if ($botApiTokenAfterUpdate) {
            $webhookUrl = $this->generateWebhookUrl($chatbot->telegramBotSecret);

            $this->client->setWebhook($botApiTokenAfterUpdate, $webhookUrl);
        }
    }

    private function saveSecret(Chatbot $chatbot, ?string $secret): void
    {
        if ($secret === $chatbot->telegramBotSecret) {
            return;
        }

        $chatbot->telegramBotSecret = $secret;

        $this->entityManager->flush();
    }

    private function generateWebhookUrl(string $secret): string
    {
        return $this->urlGenerator->generate(ChatbotController::ROUTE_NAME, [
            'secret' => $secret,
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function generateSecret(): string
    {
        return Uuid::uuid4()->toString();
    }
}
