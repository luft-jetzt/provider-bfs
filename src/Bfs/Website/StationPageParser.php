<?php declare(strict_types=1);

namespace App\Bfs\Website;

use App\Bfs\Coordinate\Converter;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class StationPageParser implements StationPageParserInterface
{
    public function __construct()
    {

    }

    public function parse(string $url): StationModel
    {
        $crawler = new Crawler($this->loadPageContent($url));
        $station = new StationModel();

        $coordinateString = $crawler->filter('table tbody tr:nth-child(4) td:nth-child(2)')->html();
        [$latitude, $longitude] = Converter::convert($coordinateString);

        $station
            ->setOperator($crawler->filter('table tbody tr:nth-child(1) td:nth-child(2)')->text())
            ->setLocation($crawler->filter('table tbody tr:nth-child(2) td:nth-child(2)')->text())
            ->setAltitude((int) $crawler->filter('table tbody tr:nth-child(3) td:nth-child(2)')->text())
            ->setLatitude($latitude)
            ->setLongitude($longitude)
        ;

        return $station;
    }

    protected function loadPageContent(string $url): string
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $url);

        return $response->getContent();
    }
}
