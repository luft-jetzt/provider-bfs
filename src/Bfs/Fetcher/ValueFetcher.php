<?php declare(strict_types=1);

namespace App\Bfs\Fetcher;

use App\Bfs\Graph\PointDetector;
use App\Bfs\Graph\StepSizeDetector;
use App\Bfs\Website\StationModel;
use Caldera\LuftModel\Model\Value;
use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Symfony\Component\HttpClient\HttpClient;

class ValueFetcher implements ValueFetcherInterface
{
    public function fromStation(StationModel $stationModel): Value
    {
        $imagine = new Imagine();

        $binaryImagecontent = $this->loadImageContent($stationModel->getCurrentImageUrl());
        $image = $imagine->load($binaryImagecontent);

        $stepSize = StepSizeDetector::detectStepSize($image);

        $currentPoint = PointDetector::detectCurrentPoint($image);

        $y = 385 - $currentPoint->getY() + 50;

        $uvIndex = round((($y / $stepSize) + 1) / 2, 2);

        $value = new Value();
        $value
            ->setStationCode($stationModel->getStationCode())
            ->setPollutant('UV')
            ->setDateTime(new \DateTime())
            ->setValue($uvIndex)
        ;

        return $value;
    }

    protected function loadImageContent(string $url): string
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', $url);

        return $response->getContent();
    }
}
