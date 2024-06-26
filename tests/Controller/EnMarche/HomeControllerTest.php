<?php

namespace Tests\App\Controller\EnMarche;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Request;
use Tests\App\AbstractEnMarcheWebTestCase;
use Tests\App\Controller\ControllerTestTrait;

#[Group('functional')]
#[Group('home')]
class HomeControllerTest extends AbstractEnMarcheWebTestCase
{
    use ControllerTestTrait;

    public function testIndex(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/');

        $this->isSuccessful($this->client->getResponse());

        // Articles
        // $this->assertSame(1, $crawler->filter('html:contains("« Je viens échanger, comprendre et construire. »")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Tribune de Richard Ferrand")')->count());

        // Live links
        $this->assertSame(1, $crawler->filter('html:contains("Guadeloupe")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Le candidat du travail")')->count());
    }

    public function testDynamicRedirections(): void
    {
        $this->client->request(Request::METHOD_GET, '/dynamic-redirection-301/?test=123');

        $this->assertClientIsRedirectedTo('/evenements', $this->client, false, true);

        $this->client->request(Request::METHOD_GET, '/dynamic-redirection-302');

        $this->assertClientIsRedirectedTo('/comites', $this->client);

        $this->client->request(Request::METHOD_GET, '/dynamic-redirection/');

        $this->assertClientIsRedirectedTo('/articles', $this->client, false, true);

        $this->client->request(Request::METHOD_GET, '/dynamic-redirection');

        $this->assertClientIsRedirectedTo('/articles', $this->client, false, true);
    }

    #[DataProvider('provideUrlsAndRedirections')]
    public function testRemoveTrailingSlashAction(string $uri, string $redirectUri)
    {
        $this->client->request(Request::METHOD_GET, $uri);

        $this->assertClientIsRedirectedTo($redirectUri, $this->client, true, true);

        $this->client->followRedirect();

        $this->isSuccessful($this->client->getResponse());
    }

    public static function provideUrlsAndRedirections(): \Generator
    {
        yield 'Emmanuel Macron' => ['/emmanuel-macron/', '/emmanuel-macron'];
        yield 'Le mouvement' => ['/le-mouvement/', '/le-mouvement'];
        yield 'Actualités' => ['/articles/actualites/', '/articles/actualites'];
    }
}
