<?php declare(strict_types=1);

namespace App\Bfs\Website;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class StationLinkExtractor implements StationLinkExtractorInterface
{
    public function __construct()
    {

    }

    public function parseStationLinks(): array
    {
        $htmlContent = $this->loadPageContent();

        $crawler = new Crawler($htmlContent);

        $links = $crawler->filter('#main #content ul li a')->each(function (Crawler $node) {
            return [
                'href' => sprintf('%s%s', self::HOSTNAME, $this->removeSessionId($node->attr('href'))),
                'caption' => $node->text(),
            ];
        });

        return $links;
    }

    protected function loadPageContent(): string
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', self::PAGE_URL);

        return $response->getContent();
    }

    protected function removeSessionId(string $url): string
    {
        return preg_replace('/;.*$/', '', $url);
    }
}
