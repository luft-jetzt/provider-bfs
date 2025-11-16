<?php declare(strict_types=1);

namespace App\Bfs\Website;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class StationLinkExtractor implements StationLinkExtractorInterface
{
    public function parseStationLinks(): array
    {
        $htmlContent = $this->loadPageContent();

        $crawler = new Crawler($htmlContent);

        $links = $crawler
            ->filter('#main #content ul li a')
            ->each(function (Crawler $node): PageLinkModel {
                $href = $this->removeSessionId($node->attr('href') ?? '');
                $url = $this->prefixHostname($href);
                $caption = $node->text();

                return new PageLinkModel($url, $caption);
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

    protected function prefixHostname(string $url): string
    {
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        if ($url !== '' && $url[0] !== '/') {
            $url = '/' . $url;
        }

        return self::HOSTNAME . $url;
    }
}
