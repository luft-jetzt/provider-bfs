<?php declare(strict_types=1);

namespace App\Bfs\Fetcher;

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

        $stepSize = $this->detectStepSize($image);

        $currentPoint = $this->detectCurrentPoint($image);

        $y = 385 - $currentPoint->getY() + 50;

        $uvIndex = (($y / $stepSize) + 1) / 2;

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

    protected function detectCurrentPoint(ImageInterface $image): Point
    {
        $size = $image->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();

        for ($x = 575; $x >= 0; --$x) {
            $point = new Point($x,  $height - 49);
            $color = $image->getColorAt($point);

            if ($color->getRed() !== $color->getGreen() || $color->getRed() !== $color->getBlue() || $color->getGreen() !== $color->getBlue()) {
                break;
            }
        }

        for ($y = $height - 49; $y > 0; --$y) {
            $point = new Point($x, $y);
            $color = $image->getColorAt($point);

            $image->draw()->dot($point, $image->palette()->color('f00'));
            $image->save('tmp4-redline.png');

            if ($color->getRed() == $color->getGreen() && $color->getRed() == $color->getBlue()) {
                ++$y;

                break;
            }
        }

        return new Point($x, $y);
    }

    protected function detectMaxUvIndex(ImageInterface $image): float
    {
        $size = $image->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();

        $counter = 1;

        for ($y = $height - 55; $y > 55; --$y) {
            $point = new Point(81, $y);
            $color = $image->getColorAt($point);

            $image->draw()->dot($point, $image->palette()->color('f00'));


            if ($color->getRed() < 230 || $color->getGreen() < 230 || $color->getRed() < 230) {
                ++$counter;
            }
        }
        $image->save('tmp4-redline.png');

        $maxUvIndex = floor(($counter - 1) / 2);

        return $maxUvIndex;
    }

    protected function detectStepSize(ImageInterface $image): float
    {
        $maxUvIndex = $this->detectMaxUvIndex($image);

        $size = round(385 / ($maxUvIndex * 2));

        return $size;
    }
}
