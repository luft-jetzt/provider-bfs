<?php

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class MaxUvIndex
{
    private const int PADDING_LEFT = 82;
    private const int THRESHOLD = 120;

    private function __construct()
    {

    }

    public static function detectMaxUvIndex(ImageInterface $image): float
    {
        $size = $image->getSize();
        $height = $size->getHeight();

        $counter = 1;

        for ($y = $height - 55; $y > 55; --$y) {
            $point = new Point(self::PADDING_LEFT, $y);
            $color = $image->getColorAt($point);

            if ($color->getRed() < self::THRESHOLD || $color->getGreen() < self::THRESHOLD || $color->getRed() < self::THRESHOLD) {
                ++$counter;

                $y -= 5; // jump five pixels down to avoid detecting some grey artifacts as scale again
            }
        }

        $maxUvIndex = floor(($counter - 1) / 2);

        return $maxUvIndex;
    }
}
