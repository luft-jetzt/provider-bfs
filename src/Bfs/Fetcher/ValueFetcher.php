<?php declare(strict_types=1);

namespace App\Bfs\Fetcher;

use App\Bfs\Graph\CurrentDateTime;
use App\Bfs\Graph\HourRange\HourRange;
use App\Bfs\Graph\Maintenance;
use App\Bfs\Graph\Point;
use App\Bfs\Graph\StepSize;
use App\Bfs\Station\StationModel;
use Caldera\LuftModel\Model\Value;
use Carbon\Carbon;
use Imagine\Gd\Imagine;
use Symfony\Component\HttpClient\HttpClient;

class ValueFetcher implements ValueFetcherInterface
{
    private const string POLLUTANT_IDENTIFIER = 'UVIndex';

    public function __construct(
        private readonly HourRange $hourRange,
        private readonly CurrentDateTime $currentDateTime,
    )
    {

    }

    public function fromStation(StationModel $stationModel): ?Value
    {
        $binaryImagecontent = $this->loadImageContent($stationModel->getCurrentImageUrl());

        $imagine = new Imagine();
        $image = $imagine->load($binaryImagecontent);

        $dateTime = $this->currentDateTime->calculate($image, $stationModel->getStationCode());

        $value = new Value();
        $value
            ->setStationCode($stationModel->getStationCode())
            ->setPollutant(self::POLLUTANT_IDENTIFIER)
            ->setDateTime($dateTime)
            ->setValue(0)
        ;

        if (Maintenance::isMaintenance($image)) {
            return null;
        }

        $hourRange = $this->hourRange->calculateAndCache($image, $stationModel->getStationCode());

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
