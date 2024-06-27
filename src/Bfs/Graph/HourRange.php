<?php declare(strict_types=1);

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;

class HourRange
{
    private const NOON_HOUR = 13;

    private function __construct()
    {

    }

    public static function calculate(ImageInterface $image): HourRangeModel
    {
        $xScaleWidth = Scale::sizeX($image);

        $startHour = self::NOON_HOUR + 1 - $xScaleWidth / 2;
        $endHour = self::NOON_HOUR + $xScaleWidth / 2;

        return new HourRangeModel($startHour, $endHour);
    }
}
