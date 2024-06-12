<?php declare(strict_types=1);

namespace App\Bfs\Website;

use CrEOF\Geo\String\Parser as GeoParser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class StationPageParser implements StationPageParserInterface
{
    public function __construct()
    {
        $this->geoParser = new GeoParser();
    }

    public function parse(string $url): StationModel
    {
        $crawler = new Crawler($this->loadPageContent($url));
        $station = new StationModel();

        $coordinateString = $crawler->filter('table tbody tr:nth-child(4) td:nth-child(2)')->html();
        [$latitude, $longitude] = $this->convertCoordinatesToDecimal($coordinateString);

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

    private function convertCoordinatesToDecimal(string $coordinateString): array
    {
        $parts = explode(' ', $coordinateString);
        dd($parts);
        $latDeg = (float) $parts[0];
        $latMin = (float) $parts[1];
        $latSec = (float) rtrim($parts[2], '"');

        $lonDeg = (float) $parts[3];
        $lonMin = (float) $parts[4];
        $lonSec = (float) rtrim($parts[5], '"');

        return [
            'lat' => (float) $latDeg + ($latMin / 60) + ($latSec / 3600),
            'lng' =>  (float) $lonDeg + ($lonMin / 60) + ($lonSec / 3600)]
        ;
    }
}