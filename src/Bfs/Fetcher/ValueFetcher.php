<?php

declare(strict_types=1);

namespace App\Bfs\Fetcher;

use App\Bfs\Graph\CurrentDateTime;
use App\Bfs\Graph\GraphDimensions;
use App\Bfs\Graph\HourRange;
use App\Bfs\Graph\Maintenance;
use App\Bfs\Graph\Point;
use App\Bfs\Graph\StepSize;
use App\Bfs\Website\StationModel;
use Caldera\LuftModel\Model\Value;
use Imagine\Gd\Imagine;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ValueFetcher implements ValueFetcherInterface
{
    private ?\DateTime $now = null;

    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    public function setNow(\DateTime $now): void
    {
        $this->now = $now;
    }

    public function fromStation(StationModel $stationModel): ?Value
    {
        $binaryImagecontent = $this->loadImageContent($stationModel->getCurrentImageUrl());

        $imagine = new Imagine();
        $image = $imagine->load($binaryImagecontent);

        $dateTime = CurrentDateTime::calculate($image);

        if (!$dateTime) {
            return null;
        }

        $value = new Value();
        $value
            ->setStationCode($stationModel->getStationCode())
            ->setPollutant('UVIndex')
            ->setDateTime($dateTime)
            ->setValue(0)
        ;

        if (Maintenance::isMaintenance($image)) {
            return null;
        }

        $hourRange = HourRange::calculate($image);

        $now = $this->now ?? new \DateTime('now', new \DateTimeZone('Europe/Berlin'));

        if ((int) $now->format('H') < $hourRange->getStartHour() || (int) $now->format('H') >= $hourRange->getEndHour()) {
            return $value;
        }

        $stepSize = StepSize::detectStepSize($image);

        $currentPoint = Point::detectCurrentPoint($image);

        $y = GraphDimensions::GRAPH_HEIGHT - $currentPoint->getY() + GraphDimensions::Y_AXIS_OFFSET;

        $uvIndex = round((($y / $stepSize) + 1) / 2, 1);

        return $value->setValue($uvIndex);
    }

    protected function loadImageContent(string $url): string
    {
        if (str_starts_with($url, 'https://')) {
            $response = $this->httpClient->request('GET', $url);

            return $response->getContent();
        }

        $content = file_get_contents($url);

        if (false === $content) {
            throw new \RuntimeException(sprintf('Could not read file: %s', $url));
        }

        return $content;
    }
}
