<?php declare(strict_types=1);

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;

class HourRange
{
    private const array RANGE_MAPPING = [
        12 => [7, 18],
        16 => [6, 21],
    ];

    private function __construct()
    {

    }

    public static function calculate(ImageInterface $image): HourRangeModel
    {
        $xScaleWidth = Scale::sizeX($image);

        if (array_key_exists($xScaleWidth, self::RANGE_MAPPING)) {
            return new HourRangeModel(
                self::RANGE_MAPPING[$xScaleWidth][0],
                self::RANGE_MAPPING[$xScaleWidth][1]
            );
        }

        throw new \Exception(sprintf('Invalid scale width %d for hour range.', $xScaleWidth));
    }
}
