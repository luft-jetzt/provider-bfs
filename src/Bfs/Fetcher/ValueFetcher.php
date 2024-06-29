<?php declare(strict_types=1);

namespace App\Bfs\Fetcher;

use App\Bfs\Graph\CurrentDateTime;
use App\Bfs\Graph\HourRange;
use App\Bfs\Graph\Maintenance;
use App\Bfs\Graph\Point;
use App\Bfs\Graph\StepSize;
use App\Bfs\Website\StationModel;
use Caldera\LuftModel\Model\Value;
use Carbon\Carbon;
use Imagine\Gd\Imagine;
use Symfony\Component\HttpClient\HttpClient;

class ValueFetcher implements ValueFetcherInterface
{
    public function fromStation(StationModel $stationModel): ?Value
    {
        $binaryImagecontent = $this->loadImageContent($stationModel->getCurrentImageUrl());

        $imagine = new Imagine();
        $image = $imagine->load($binaryImagecontent);

        $dateTime = CurrentDateTime::calculate($image);

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

        $now = new Carbon();

        if ($now->format('H') < $hourRange->getStartHour() || $now->format('H') >= $hourRange->getEndHour()) {
            return $value;
        }

        $stepSize = StepSize::detectStepSize($image);

        $currentPoint = Point::detectCurrentPoint($image);

        $y = 385 - $currentPoint->getY() + 50;

        $uvIndex = round((($y / $stepSize) + 1) / 2, 1);

        return $value->setValue($uvIndex);
    }

    protected function loadImageContent(string $url): string
    {
        if (str_starts_with($url, 'https://')) {
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', $url);

            return $response->getContent();
        }

        return file_get_contents($url);
    }
}
